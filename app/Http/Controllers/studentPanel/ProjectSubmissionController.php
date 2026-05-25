<?php

namespace App\Http\Controllers\studentPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProjectSubmissionController extends Controller
{
    // Aapki aik nayi sheet banani hogi submissions k liay
    private $submissionSheetId = "1nZfLVzddZ2xcqolUKAH8KksNKTOJiTBA2HahB2ld5tQ"; 

    // public function index()
    // {
    //     if (!session('student_logged_in')) {
    //         return redirect()->route('student.login.view');
    //     }
    //     return view('studentPanel.project');
    // }
    public function index()
{
    $token = $this->getAccessToken();
    $roll = session('student_roll');
    
    // Sheet se data fetch karein
    $response = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:J"
    );
    
    $rows = $response->json()['values'] ?? [];
    $existingData = null;

    // Search for the student's row
    foreach ($rows as $row) {
        if (isset($row[0]) && trim($row[0]) == trim($roll)) {
            $existingData = [
                'roll' => $row[0],
                'name' => $row[1],
                'title' => $row[3],
                'link' => $row[4],
                'desc' => $row[5] ?? '',
                'status' => $row[7] ?? 'Pending',
                'remarks' => $row[8] ?? ''
            ];
            break;
        }
    }

    return view('studentPanel.project', compact('existingData'));
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'drive_link' => 'required|url',
    ]);

    $token = $this->getAccessToken();
    $roll = session('student_roll');

    // 1. Pehle sari rows fetch karein taake hum check kar sakein k student pehle se majood hy ya nahi
    $response = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:J"
    );
    
    $rows = $response->json()['values'] ?? [];
    $rowIndex = -1;

    // 2. Loop chala kar student ka Roll Number dhoondein
    foreach ($rows as $index => $row) {
        // Index 0 matlab Column A (Roll Number)
        if (isset($row[0]) && trim($row[0]) == trim($roll)) {
            $rowIndex = $index + 1; // Google Sheets 1-based index use karta hy
            break;
        }
    }

    // 3. Data prepare karein (Jaisa aap ne Column A se J tak headers banaye hain)
    $values = [
        [
            $roll,                         // A
            session('student_name'),       // B
            session('student_email'),      // C
            $request->title,               // D
            $request->drive_link,          // E
            $request->description,         // F
            date('Y-m-d H:i:s'),           // G
            'Pending',                     // H (Update par status wapis pending ho jaye ga)
            '',                            // I (Purane remarks clear)
            session('supervisor_name')     // J
        ]
    ];

    if ($rowIndex > 0) {
        // 4. AGAR STUDENT MIL GAYA TO USI ROW KO UPDATE KAREIN (PUT Request)
        // Range hogi: Sheet1!A{row}:J{row}
        $range = "Sheet1!A{$rowIndex}:J{$rowIndex}";
        
        $updateResponse = Http::withToken($token)->put(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/{$range}?valueInputOption=USER_ENTERED",
            ["values" => $values]
        );

        $message = "Sir, your project details have been Updated.";
    } else {
        // 5. AGAR NAYA STUDENT HY TO NAYI ROW ADD KAREIN (POST Request)
        $appendResponse = Http::withToken($token)->post(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->submissionSheetId}/values/Sheet1!A:J:append?valueInputOption=USER_ENTERED",
            ["values" => $values]
        );
        
        $message = "Sir, your project has been SUBMITTED successfully!";
    }

    return redirect()->route('student.project.view')->with('success', $message);
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
}