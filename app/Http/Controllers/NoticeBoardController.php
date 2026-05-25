<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NoticeBoardController extends Controller
{
    // Sir, aapki Notice Board wali Sheet ID
    private $noticeSheetId = "1AExXb0F0XZbEzYVQx-0_jEhD6Yxqmokw4bOwwpmgQkA";

    /**
     * Display the Public Notice Board
     */
    public function index(Request $request)
{
    $token = $this->getAccessToken();
    $response = Http::withToken($token)->get(
        "https://sheets.googleapis.com/v4/spreadsheets/{$this->noticeSheetId}/values/Sheet1!A:E"
    );

    $allRows = $response->json()['values'] ?? [];
    $notices = (count($allRows) > 1) ? array_reverse(array_slice($allRows, 1)) : [];

    // Sir, URL check karne ka sab se pakka tareeqa ye hai:
    if ($request->is('supervisor/*') || $request->segment(1) == 'supervisor') {
        return view('supervisorPanel.notices', compact('notices'));
    }

    if ($request->is('student/*') || $request->segment(1) == 'student') {
        return view('studentPanel.notices', compact('notices'));
    }

    // Default (safety ke liye)
    return view('studentPanel.notices', compact('notices'));
}

    /**
     * Generate Access Token using Refresh Token from .env
     */
    private function getAccessToken() {
        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id'     => env('GOOGLE_DRIVE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
                'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
                'grant_type'    => 'refresh_token',
            ]);

            return $response->json()['access_token'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}