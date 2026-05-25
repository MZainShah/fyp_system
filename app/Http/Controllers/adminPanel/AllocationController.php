<?php

namespace App\Http\Controllers\adminPanel;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AllocationController extends AdminBaseController
{
    private $studentSheetId = "1XqZ0Yi28YMxL9oj8-mAPzYgVHAp0a_egzGoDFrcvbpQ";
    private $supervisorSheetId = "1e3q80wL8lNWg4vhdyW9XVi1XbA-QOgHhJc8cEisrjNw";
    private $allocationSheetId = "1jcU2shJHtGcttBbF9fZP440zPqc3pLMY9017VY_rzyY";

    /* ==========================
       GOOGLE ACCESS TOKEN
    ========================== */
    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ]);

        return $response->json()['access_token'];
    }

    /* ==========================
       GET STUDENTS
    ========================== */
    private function getStudents()
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->studentSheetId}/values/Sheet1!A:F"
        );

        $rows = $response->json()['values'] ?? [];
        $students = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue;
            $students[] = [
                'id' => $row[0] ?? '',
                'name' => $row[1] ?? '',
                'roll' => $row[2] ?? ''
            ];
        }
        return $students;
    }

    /* ==========================
       GET SUPERVISORS
    ========================== */
    private function getSupervisors()
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->supervisorSheetId}/values/Sheet1!A:E"
        );

        $rows = $response->json()['values'] ?? [];
        $supervisors = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue;
            $supervisors[] = [
                'id' => $row[0] ?? '',
                'name' => $row[1] ?? ''
            ];
        }
        return $supervisors;
    }

    /* ==========================
       CLEAR ALLOCATION SHEET
    ========================== */
    private function clearAllocation()
    {
        $token = $this->getAccessToken();

        // Changed 'allocation' to 'Sheet1' to match your tab name
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->allocationSheetId}/values/Sheet1!A2:E:clear";

        Http::withToken($token)->post($url);
    }

    /* ==========================
       SAVE ALLOCATION
    ========================== */

    private function saveAllocation($data)
    {
        $token = $this->getAccessToken();

        // Changed 'allocation' to 'Sheet1' here as well
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->allocationSheetId}/values/Sheet1!A2:E?valueInputOption=USER_ENTERED";

        $body = [
            "range" => "Sheet1!A2:E",
            "majorDimension" => "ROWS",
            "values" => $data
        ];

        $response = Http::withToken($token)->put($url, $body);

        if (!$response->successful()) {
            Log::error("Google Sheets Error: " . $response->body());
            return false;
        }

        return true;
    }

    /* ==========================
       VIEW PAGE
    ========================== */
    public function index()
    {
        return view('adminPanel.allocation');
    }

    /* ==========================
       RUN ALLOCATION
    ========================== */
    public function allocate()
    {
        $students = $this->getStudents();
        $supervisors = $this->getSupervisors();

        if (count($students) == 0 || count($supervisors) == 0) {
            return back()->with('error', 'Students or supervisors missing.');
        }

        shuffle($students);
        $totalSupervisors = count($supervisors);
        $result = [];

        foreach ($students as $index => $student) {
            $supervisor = $supervisors[$index % $totalSupervisors];
            $result[] = [
                $student['id'],
                $student['name'],
                $student['roll'],
                $supervisor['id'],
                $supervisor['name']
            ];
        }

        // Logic sequence: Clear old data first, then save new data
        $this->clearAllocation();
        $saved = $this->saveAllocation($result);

        if ($saved) {
            return back()->with('success', 'Students allocated successfully.');
        } else {
            return back()->with('error', 'Failed to save allocation to Google Sheets.');
        }
    }


    public function supervisorsAllocation()
    {
        $supervisors = $this->getSupervisors();

        return view('adminPanel.allocatedSupervisors', compact('supervisors'));
    }


    public function viewAllocatedStudents($supervisorId)
{
    $token = $this->getAccessToken();

    $response = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->allocationSheetId}/values/Sheet1!A:E"
    );

    if (!$response->successful()) {
        return back()->with('error','Failed to read allocation sheet.');
    }

    $rows = $response->json()['values'] ?? [];

    $students = [];
    $supervisorName = "";

    foreach ($rows as $index => $row) {

        if ($index == 0) continue; // skip header

        if (($row[3] ?? '') == $supervisorId) {

            $students[] = [
                'name' => $row[1] ?? '',
                'roll' => $row[2] ?? ''
            ];

            $supervisorName = $row[4] ?? '';
        }
    }

    return view(
        'adminPanel.allocatedStudents',
        compact('students','supervisorName')
    );
}

}
