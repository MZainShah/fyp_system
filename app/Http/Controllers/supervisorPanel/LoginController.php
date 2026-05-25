<?php

namespace App\Http\Controllers\supervisorPanel;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Sir, yahan sirf login verification ke liye supervisor list wali sheet chahiye
    private $supervisorSheetId = "1e3q80wL8lNWg4vhdyW9XVi1XbA-QOgHhJc8cEisrjNw";

    /* ==========================
       1. SHOW LOGIN VIEW
    ========================== */
    public function showLoginForm()
    {
        return view('supervisorPanel.login');
    }

    /* ==========================
       2. REDIRECT TO GOOGLE
    ========================== */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /* ==========================
       3. HANDLE GOOGLE CALLBACK
    ========================== */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            $token = $this->getAccessToken();

            // Supervisor Sheet se data fetch karna
            $response = Http::withToken($token)->get(
                "https://sheets.googleapis.com/v4/spreadsheets/{$this->supervisorSheetId}/values/Sheet1!A:E"
            );
            
            $rows = $response->json()['values'] ?? [];
            $foundSupervisor = null;

            foreach ($rows as $index => $row) {
                if ($index == 0) continue; 

                if (in_array(strtolower($email), array_map('strtolower', $row))) {
                    $foundSupervisor = $row;
                    break;
                }
            }

            if ($foundSupervisor) {
                // Login Success: Session data save karein
                Session::put('supervisor_logged_in', true);
                Session::put('supervisor_id', $foundSupervisor[0] ?? ''); 
                Session::put('supervisor_name', $foundSupervisor[1] ?? 'Supervisor'); 
                Session::put('supervisor_email', $email);

                // Sir, ab redirect Dashboard Controller ke route par jaye ga
                return redirect()->route('supervisor.dashboard')->with('success', 'Welcome Sir!');
            } else {
                return redirect()->route('supervisor.login')->with('error', 'Sir, your email is not registered as a supervisor.');
            }

        } catch (\Exception $e) {
            Log::error("Google Login Error: " . $e->getMessage());
            return redirect()->route('supervisor.login')->with('error', 'Something went wrong. Please try again.');
        }
    }

    /* ==========================
       4. LOGOUT LOGIC
    ========================== */
    public function logout()
    {
        Session::forget(['supervisor_logged_in', 'supervisor_id', 'supervisor_name', 'supervisor_email']);
        return redirect()->route('supervisor.login')->with('success', 'Logged out successfully.');
    }

    /* ==========================
       PRIVATE: GET ACCESS TOKEN
    ========================== */
    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error("Failed to refresh Google Token: " . $response->body());
        return null;
    }
}