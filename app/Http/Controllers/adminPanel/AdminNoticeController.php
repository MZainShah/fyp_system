<?php

namespace App\Http\Controllers\adminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminNoticeController extends Controller
{
    private $noticeSheetId = "1AExXb0F0XZbEzYVQx-0_jEhD6Yxqmokw4bOwwpmgQkA";

    // 1. Existing: Notice Create karne ka form
    public function create() {
        return view('adminPanel.notices');
    }

    // 2. Existing: Notice Sheet mein save karna
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'doc_link' => 'nullable|url'
        ]);

        $token = $this->getAccessToken();
        if (!$token) return back()->withErrors(['error' => 'Sir, Access Token issue!']);

        $values = [[
            date('Y-m-d'),
            session('admin_name') ?? 'Admin',
            $request->title,
            $request->message,
            $request->doc_link
        ]];

        $response = Http::withToken($token)->post(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->noticeSheetId}/values/Sheet1!A:E:append?valueInputOption=USER_ENTERED",
            ["values" => $values]
        );

        return back()->with('success', 'Sir, Notice published successfully!');
    }

    // 3. NEW: Tamam notices dikhane ke liye (View All Tab)
    public function index() {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$this->noticeSheetId}/values/Sheet1!A:E");

        $allRows = $response->json()['values'] ?? [];
        $notices = [];
        
        if (count($allRows) > 1) {
            foreach (array_slice($allRows, 1) as $key => $row) {
                // image_10f83c.png ke mutabiq row index set kar rahe hain
                $row['row_id'] = $key + 2; 
                $notices[] = $row;
            }
            $notices = array_reverse($notices);
        }

        return view('adminPanel.viewAllNotices', compact('notices'));
    }

    // 4. NEW: Edit Form dikhane ke liye
    public function edit($row) {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$this->noticeSheetId}/values/Sheet1!A{$row}:E{$row}");
        $notice = $response->json()['values'][0] ?? null;

        return view('adminPanel.editNotice', compact('notice', 'row'));
    }

    // 5. NEW: Data Update karne ke liye
    public function update(Request $request, $row) {
        $token = $this->getAccessToken();
        
        $values = [[
            $request->date,
            $request->sender,
            $request->title,
            $request->message,
            $request->doc_link
        ]];

        Http::withToken($token)->put(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->noticeSheetId}/values/Sheet1!A{$row}:E{$row}?valueInputOption=USER_ENTERED",
            ['values' => $values]
        );

        return redirect()->route('admin.notices.index')->with('success', 'Sir, Notice update ho gaya!');
    }

    // Token Logic (Shared)
    private function getAccessToken() {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id'     => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type'    => 'refresh_token',
        ]);
        return $response->json()['access_token'] ?? null;
    }
}