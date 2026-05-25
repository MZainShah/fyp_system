<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GoogleDriveService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $folderId;

    public function __construct()
    {
        $this->clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $this->refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
        $this->folderId = env('GOOGLE_DRIVE_ROOT_FOLDER_ID');
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token',
        ]);
        return $response->json()['access_token'];
    }

    // File dhoondne ke liye function
    public function findFile($fileName)
    {
        $token = $this->getAccessToken();
        $query = "name = '$fileName' and '{$this->folderId}' in parents and trashed = false";

        $response = Http::withToken($token)->get("https://www.googleapis.com/drive/v3/files", [
            'q' => $query,
            'fields' => 'files(id, name)',
        ]);

        return $response->json()['files'][0] ?? null;
    }

    // File ka data read karne ke liye
    public function getFileData($fileId)
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get("https://www.googleapis.com/drive/v3/files/$fileId?alt=media");
        return $response->json();
    }

    // File save/update karne ke liye
    public function saveJson($fileName, $data)
    {
        $token = $this->getAccessToken();
        $existingFile = $this->findFile($fileName);

        if ($existingFile) {
            // Update existing file
            return Http::withToken($token)
                ->withBody(json_encode($data), 'application/json')
                ->patch("https://www.googleapis.com/upload/drive/v3/files/{$existingFile['id']}?uploadType=media");
        } else {
            // Create new file
            return Http::withToken($token)
                ->attach('metadata', json_encode(['name' => $fileName, 'parents' => [$this->folderId]]), 'metadata.json', ['Content-Type' => 'application/json'])
                ->attach('file', json_encode($data), $fileName, ['Content-Type' => 'application/json'])
                ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');
        }
    }

    public function emailExists($email, $ignoreId = null)
    {
        $supervisors = $this->getAllSupervisors();

        foreach ($supervisors as $supervisor) {
            if (
                strtolower($supervisor['email']) === strtolower($email) &&
                $supervisor['id'] !== $ignoreId
            ) {
                return true;
            }
        }

        return false;
    }

    public function addSupervisor($name, $email, $role)
    {
        $token = $this->getAccessToken();
        $sheetId = env('GOOGLE_SHEET_ID');

        $id = Str::uuid()->toString(); // unique ID
        $createdAt = Carbon::now()->format('Y-m-d');

        $response = Http::withToken($token)->post(
            "https://sheets.googleapis.com/v4/spreadsheets/$sheetId/values/Sheet1!A:E:append?valueInputOption=RAW",
            [
                'values' => [
                    [$id, $name, $email, $role, $createdAt]
                ]
            ]
        );

        if (!$response->successful()) {
            dd($response->status(), $response->body());
        }

        return true;
    }

    public function getAllSupervisors()
    {
        $token = $this->getAccessToken();
        $sheetId = env('GOOGLE_SHEET_ID');

        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/$sheetId/values/Sheet1!A:E"
        );

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch supervisors');
        }

        $values = $response->json()['values'] ?? [];

        $supervisors = [];

        foreach ($values as $index => $row) {

            if ($index === 0) continue; // skip header

            $supervisors[] = [
                'id'         => $row[0] ?? '',
                'row'        => $index + 1, // important
                'name'       => $row[1] ?? '',
                'email'      => $row[2] ?? '',
                'role'       => $row[3] ?? '',
                'created_at' => $row[4] ?? '',
            ];
        }

        return $supervisors;
    }

    public function deleteSupervisor($row)
    {
        $token = $this->getAccessToken();
        $sheetId = env('GOOGLE_SHEET_ID');

        $range = "Sheet1!A{$row}:E{$row}";

        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post(
                "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$range}:clear",
                new \stdClass() // 👈 forces {} instead of []
            );

        if (!$response->successful()) {
            dd($response->status(), $response->body());
        }

        return true;
    }

    public function updateSupervisor($row, $id, $name, $email, $role)
    {
        $token = $this->getAccessToken();
        $sheetId = env('GOOGLE_SHEET_ID');

        $response = Http::withToken($token)->put(
            "https://sheets.googleapis.com/v4/spreadsheets/$sheetId/values/Sheet1!A{$row}:E{$row}?valueInputOption=RAW",
            [
                'values' => [
                    [$id, $name, $email, $role, now()->format('Y-m-d')]
                ]
            ]
        );

        return $response->successful();
    }

    public function appendStudents($students)
    {
        $token = $this->getAccessToken();
        $sheetId = env('GOOGLE_STUDENT_SHEET_ID');

        $response = Http::withToken($token)->post(
            "https://sheets.googleapis.com/v4/spreadsheets/$sheetId/values/Sheet1!A:F:append?valueInputOption=RAW",
            [
                'values' => $students
            ]
        );

        if (!$response->successful()) {
            dd($response->status(), $response->body());
        }

        return true;
    }
}
