<?php
namespace App\Http\Controllers\supervisorPanel;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SupervisorDashboardController extends Controller
{
    private $submissionSheetId = "1nZfLVzddZ2xcqolUKAH8KksNKTOJiTBA2HahB2ld5tQ"; 

    private function getAccessToken() {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ]);

        return $response->successful() ? $response->json()['access_token'] : null;
    }

    public function index()
    {
        if (!Session::has('supervisor_logged_in')) {
            return redirect()->route('supervisor.login')->with('error', 'Sir, please login first.');
        }

        $token = $this->getAccessToken();
        $supervisorName = Session::get('supervisor_name'); 

        // UPDATED: Range A:J se badal kar A:K kar di gayi hai
        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:K"
        );

        $rows = $response->json()['values'] ?? [];
        $myStudents = [];
        $stats = ['total' => 0, 'pending' => 0, 'approved' => 0, 'today' => 0];

        if (count($rows) > 1) { 
            foreach (array_slice($rows, 1) as $row) {
                if (isset($row[9]) && trim($row[9]) == trim($supervisorName)) {
                    
                    $status = $row[7] ?? 'Pending';
                    $submitDate = isset($row[6]) ? date('d M, Y', strtotime($row[6])) : 'N/A';

                    $myStudents[] = [
                        'roll'    => $row[0] ?? '',
                        'name'    => $row[1] ?? '',
                        'email'   => $row[2] ?? '',
                        'title'   => $row[3] ?? '',
                        'link'    => $row[4] ?? '',
                        'desc'    => $row[5] ?? '',
                        'date'    => $submitDate,
                        'status'  => $status,
                        'remarks' => $row[8] ?? '',
                        // UPDATED: Column K (Index 10) se marks uthaye ja rahe hain
                        'marks'   => $row[10] ?? '' 
                    ];

                    $stats['total']++;
                    if ($status == 'Pending') $stats['pending']++;
                    if ($status == 'Approved') $stats['approved']++;
                    if (isset($row[6]) && strpos($row[6], date('Y-m-d')) !== false) {
                        $stats['today']++;
                    }
                }
            }
        }
        return view('supervisorPanel.dashboard', compact('myStudents', 'stats'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'roll'    => 'required',
            'status'  => 'required',
            'remarks' => 'nullable',
            'marks'   => 'nullable|numeric|min:0|max:100'
        ]);

        $token = $this->getAccessToken();
        $rollToFind = $request->roll;

        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:A"
        );

        $rows = $response->json()['values'] ?? [];
        $rowIndex = -1;

        foreach ($rows as $index => $row) {
            if (isset($row[0]) && trim($row[0]) == trim($rollToFind)) {
                $rowIndex = $index + 1; 
                break;
            }
        }

        if ($rowIndex == -1) {
            return back()->with('error', 'Sir, student record not found.');
        }

        // Supervisor Name mehfooz rakhne ke liye fetch karein
        $currentDataResponse = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!J{$rowIndex}"
        );
        $currentSupervisor = $currentDataResponse->json()['values'][0][0] ?? 'N/A';

        // UPDATE: H se K tak ka data
        $updateData = [
            'values' => [[
                $request->status,           // Col H
                $request->remarks ?? '',    // Col I
                $currentSupervisor,         // Col J
                $request->marks ?? ''       // Col K
            ]]
        ];

        $updateResponse = Http::withToken($token)
            ->withBody(json_encode($updateData), 'application/json')
            ->put("https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!H{$rowIndex}:K{$rowIndex}?valueInputOption=USER_ENTERED");

        if ($updateResponse->successful()) {
            return back()->with('success', "Sir, Roll No {$rollToFind} ka record update ho gaya hai.");
        }

        return back()->with('error', 'Something went wrong.');
    }
}