<?php

namespace App\Http\Controllers\supervisorPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SupervisorStudentDirectoryController extends Controller
{
    // Sir, IDs check kar lijiye ga apni sheets ke mutabiq
    private $allocationSheetId = "1jcU2shJHtGcttBbF9fZP440zPqc3pLMY9017VY_rzyY";
    private $submissionSheetId = "1nZfLVzddZ2xcqolUKAH8KksNKTOJiTBA2HahB2ld5tQ";

    private function getAccessToken() {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ]);
        return $response->json()['access_token'] ?? null;
    }

    public function index()
    {
        if (!Session::has('supervisor_logged_in')) {
            return redirect()->route('supervisor.login')->with('error', 'Please login first.');
        }

        $token = $this->getAccessToken();
        $supervisorName = Session::get('supervisor_name');

        // 1. Allocation Sheet se saare assigned students uthayein (Sheet1!A:E)
        $allStudentsResp = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->allocationSheetId}/values/Sheet1!A:E"
        );
        $allStudentsRows = $allStudentsResp->json()['values'] ?? [];

        // 2. Submission Sheet se status check karne ke liye data uthayein (Sheet1!A:I)
        $submissionsResp = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:I"
        );
        $submissionsRows = $submissionsResp->json()['values'] ?? [];

        // Submissions ko map karein (Roll No => Status)
        $submissionStatusMap = [];
        foreach (array_slice($submissionsRows, 1) as $sub) {
            $submissionStatusMap[trim($sub[0])] = $sub[7] ?? 'Pending'; 
        }

        $directoryList = [];
        foreach (array_slice($allStudentsRows, 1) as $row) {
            // Check karein ke supervisor match ho raha hai (Column E - Index 4)
            if (isset($row[4]) && trim($row[4]) == trim($supervisorName)) {
                $roll = trim($row[2]); // Roll No (Column C - Index 2)
                
                $directoryList[] = [
                    'roll' => $roll,
                    'name' => $row[1] ?? 'N/A',
                    'status' => $submissionStatusMap[$roll] ?? 'Not Submitted Yet'
                ];
            }
        }

        return view('supervisorPanel.studentDirectory', compact('directoryList'));
    }
}