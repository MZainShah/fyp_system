<?php

namespace App\Http\Controllers\studentPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SrsSddController extends Controller
{
    // Jahan data save karna hai (Nayi Sheet)
    private $newSheetId = "1uhsGY4M_ulsyIU5H_fMU-vs9_oIQ7UKOW3cBC_BrZ2g"; 
    // Jahan se Project Title uthana hai (Submission Sheet)
    private $submissionSheetId = "1nZfLVzddZ2xcqolUKAH8KksNKTOJiTBA2HahB2ld5tQ"; 

public function index()
{
    if (!Session::get('student_logged_in')) {
        return redirect()->route('student.login.view');
    }

    $token = $this->getAccessToken();
    $roll = trim(Session::get('student_roll'));
    $name = Session::get('student_name');

    // --- 1. Purani Submission Sheet se sirf Title aur Status lena hai ---
    $projResponse = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:H"
    );
    $projRows = $projResponse->json()['values'] ?? [];
    
    $projectTitle = "N/A";
    $projectStatus = "Pending";

    foreach ($projRows as $row) {
        if (isset($row[0]) && trim($row[0]) == $roll) {
            $projectTitle = $row[3] ?? 'No Title Found';
            $projectStatus = $row[7] ?? 'Pending';
            break;
        }
    }

    // --- 2. Nayi SRS/SDD Sheet (newSheetId) se actual Feedback nikalna ---
    // Range ko A:F kar diya taake Remarks (E) aur Marks (F) mil sakein
    $subResponse = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->newSheetId}/values/Sheet1!A:F"
    );
    $allSubmissions = $subResponse->json()['values'] ?? [];
    
    $mySubmission = null;
    foreach ($allSubmissions as $sub) {
        if (isset($sub[0]) && trim($sub[0]) == $roll) {
            $mySubmission = [
                'roll'    => $sub[0],
                'name'    => $sub[1],
                'srs'     => $sub[2],
                'sdd'     => $sub[3],
                'remarks' => $sub[4] ?? 'No feedback provided yet for SRS/SDD.', // Column E
                'marks'   => $sub[5] ?? 'N/A'                                   // Column F
            ];
            break;
        }
    }

    return view('studentPanel.srssdd', compact(
        'name', 
        'roll', 
        'projectTitle', 
        'projectStatus', 
        'mySubmission'
    ));
}

public function store(Request $request)
{
    $token = $this->getAccessToken();
    
    // Check karein ke session mein data hai ya nahi
    $roll = trim(Session::get('student_roll'));
    $name = Session::get('student_name');

    if (!$roll || !$name) {
        return "Session expired! Please login again.";
    }

    $values = [[
        $roll,
        $name,
        $request->srs_link,
        $request->sdd_link
    ]];

    // Sheet ID aur Range ko double check karein
    $spreadsheetId = "1uhsGY4M_ulsyIU5H_fMU-vs9_oIQ7UKOW3cBC_BrZ2g";
    $range = "Sheet1!A:D"; 

    $response = Http::withToken($token)->post(
        "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}:append?valueInputOption=USER_ENTERED",
        ['values' => $values]
    );

    // AGAR ERROR AAYE TOH YEH LINE SCREEN PAR ERROR DIKHAYE GI
    if ($response->failed()) {
        return dd([
            'Error_Message' => 'Google API Rejected Request',
            'Status' => $response->status(),
            'Google_Response' => $response->json()
        ]);
    }

    return redirect()->back()->with('success', 'Sir, data saved successfully!');
}

    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ]);
        return $response->json()['access_token'] ?? null;
    }

    public function update(Request $request)
{
    $token = $this->getAccessToken();
    $roll = trim(Session::get('student_roll'));

    // Fetch all rows to find the index
    $response = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->newSheetId}/values/Sheet1!A:D"
    );
    $rows = $response->json()['values'] ?? [];

    $rowIndex = -1;
    foreach ($rows as $index => $row) {
        if (isset($row[0]) && trim($row[0]) == $roll) {
            $rowIndex = $index + 1; // Google Sheets 1-based index
            break;
        }
    }

    if ($rowIndex != -1) {
        // Range: C (SRS) and D (SDD) for that specific row
        $range = "Sheet1!C{$rowIndex}:D{$rowIndex}";
        Http::withToken($token)->put(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->newSheetId}/values/{$range}?valueInputOption=RAW",
            [
                'values' => [[$request->srs_link, $request->sdd_link]]
            ]
        );
        return back()->with('success', 'Documentation links updated successfully, sir!');
    }

    return back()->with('error', 'Record not found.');
}
}