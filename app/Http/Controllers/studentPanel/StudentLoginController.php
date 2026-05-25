<?php

namespace App\Http\Controllers\studentPanel;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class StudentLoginController extends Controller
{
    private $studentSheetId = "1XqZ0Yi28YMxL9oj8-mAPzYgVHAp0a_egzGoDFrcvbpQ";
    private $allocationSheetId = "1jcU2shJHtGcttBbF9fZP440zPqc3pLMY9017VY_rzyY";

    // 1. Redirect to Google
    public function redirectToGoogle()
{
    // Hum Socialite ko bata rahy hain k 'google' driver use karo 
    // magar config 'services.google_student' wali uthao
    return Socialite::buildProvider(
        \Laravel\Socialite\Two\GoogleProvider::class,
        config('services.google_student')
    )->redirect();
}

    // 2. Handle Google Callback
    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::buildProvider(
            \Laravel\Socialite\Two\GoogleProvider::class,
            config('services.google_student')
        )->user();

        $email = strtolower($googleUser->getEmail());
        $token = $this->getAccessToken();
        
        // 1. Student List wali sheet se basic data uthana
        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->studentSheetId}/values/Sheet1!A:F"
        );

        $rows = $response->json()['values'] ?? [];
        $foundStudent = null;

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; 
            if (isset($row[3]) && strtolower($row[3]) == $email) {
                $foundStudent = $row;
                break;
            }
        }

        if ($foundStudent) {
            $studentRoll = $foundStudent[2]; // Roll number uthaya

            // ============= NAYA CODE START (Allocation Check) =============
            $allocResponse = Http::withToken($token)->get(
                "https://sheets.googleapis.com/v4/spreadsheets/{$this->allocationSheetId}/values/Sheet1!A:E"
            );
            
            $allocRows = $allocResponse->json()['values'] ?? [];
            $supervisorName = "Not Assigned Yet"; // Default value

            foreach ($allocRows as $row) {
                // Column C (index 2) Roll Number hy aur Column E (index 4) Supervisor Name
                if (isset($row[2]) && trim($row[2]) == trim($studentRoll)) {
                    $supervisorName = $row[4]; // Supervisor mil gaya!
                    break;
                }
            }
            // ============= NAYA CODE END =================================

            // Session mein data save karein
            Session::put('student_logged_in', true);
            Session::put('student_name', $foundStudent[1]);
            Session::put('student_roll', $studentRoll);
            Session::put('student_email', $email);
            Session::put('supervisor_name', $supervisorName); // Supervisor session mein dal dia

            return redirect()->route('student.dashboard');
        }

        return redirect()->route('student.login.view')->with('error', 'Sir, your IUB email is not in our records.');

    } catch (\Exception $e) {
        return redirect()->route('student.login.view')->with('error', 'Login failed: ' . $e->getMessage());
    }
}

    // Helper: Get Access Token
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

    
public function dashboard()
{
    // 1. Pehle check karein ke student logged in hai ya nahi
    if (!Session::get('student_logged_in')) {
        return redirect()->route('student.login.view')->with('error', 'Sir, please login first.');
    }

    $token = $this->getAccessToken();
    $roll = session('student_roll');
    
    // Aapki real Submission Sheet ID
    $submissionSheetId = "1nZfLVzddZ2xcqolUKAH8KksNKTOJiTBA2HahB2ld5tQ";

    // 2. Submissions fetch karein (A:K range tak taake marks bhi mil jayein)
    $response = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$submissionSheetId}/values/Sheet1!A:K"
    );
    
    $rows = $response->json()['values'] ?? [];
    $projectDetails = null;

    // 3. Student ki row find karein
    foreach ($rows as $row) {
        if (isset($row[0]) && trim($row[0]) == trim($roll)) {
            $projectDetails = [
                'title'   => $row[3] ?? 'Project Proposal / Idea',
                'status'  => $row[7] ?? 'Pending',
                'remarks' => $row[8] ?? '',
                'marks'   => $row[10] ?? 'N/A' // Column K (index 10)
            ];
            break;
        }
    }

    // 4. Data pass karein view ko
    return view('studentPanel.dashboard', compact('projectDetails'));
}


    public function logout()
{
    Session::forget(['student_logged_in', 'student_name', 'student_roll', 'student_email', 'supervisor_name']);
    return redirect()->route('student.login.view')->with('success', 'Logged out successfully.');
}
}