<?php

namespace App\Http\Controllers\adminPanel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private $noticeSheetId = "1AExXb0F0XZbEzYVQx-0_jEhD6Yxqmokw4bOwwpmgQkA";

    public function index()
{
    if (session('role') !== 'admin') {
        return redirect('/admin/login');
    }

    $token = $this->getAccessToken();
    $totalNotices = 0;
    $recentNotices = [];

    if ($token) {
        $response = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$this->noticeSheetId}/values/Sheet1!A:E");
        $allRows = $response->json()['values'] ?? [];
        
        if (count($allRows) > 1) {
            $noticeRows = array_slice($allRows, 1);
            $totalNotices = count($noticeRows);
            
            foreach ($noticeRows as $row) {
                $recentNotices[] = $row;
            }
            $recentNotices = array_reverse($recentNotices);
            $recentNotices = array_slice($recentNotices, 0, 5);
        }
    }

    return view('adminPanel.dashboard', compact('totalNotices', 'recentNotices'));
}

    // Token Logic for Google Sheets API
    private function getAccessToken() {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id'     => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type'    => 'refresh_token',
        ]);
        return $response->json()['access_token'] ?? null;
    }

public function downloadMarksheet()
{
    if (session('role') !== 'admin') {
        return redirect('/admin/login');
    }

    // Sir, yahan apni exact SRS/SDD Marks wali Google Sheet ID lagayein
    $marksheetSheetId = "1uhsGY4M_ulsyIU5H_fMU-vs9_oIQ7UKOW3cBC_BrZ2g"; 
    $token = $this->getAccessToken();

    if (!$token) {
        return redirect()->back()->with('error', 'Google API Token issue!');
    }

    // Google Sheet se Columns A se F tak ka saara data uthayein (Sare students ka)
    $response = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$marksheetSheetId}/values/Sheet1!A:F");
    $rows = $response->json()['values'] ?? [];

    if (empty($rows)) {
        return redirect()->back()->with('error', 'Sir, marksheet data khali hai ya Sheet Name galat hai!');
    }

    // 1. Nayi Excel Spreadsheet initialize karein
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // 2. Excel ke main headers set karein
    $sheet->setCellValue('A1', 'Roll Number');
    $sheet->setCellValue('B1', 'Student Name');
    $sheet->setCellValue('C1', 'SRS Link');
    $sheet->setCellValue('D1', 'SDD Link');
    $sheet->setCellValue('E1', 'Supervisor Remarks');
    $sheet->setCellValue('F1', 'Obtained Marks');

    // Headers ko bold karein design behter karne ke liye
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    // 3. Poori Google Sheet ka loop chalayein (Bina filter lagaye - All Students)
    $rowCount = 2;
    // index 0 nikalne ke liye array_slice lagaya kyunke wo header row thi
    foreach (array_slice($rows, 1) as $row) {
        $sheet->setCellValue('A' . $rowCount, $row[0] ?? 'N/A'); // Roll
        $sheet->setCellValue('B' . $rowCount, $row[1] ?? 'N/A'); // Name
        $sheet->setCellValue('C' . $rowCount, $row[2] ?? '#');   // SRS
        $sheet->setCellValue('D' . $rowCount, $row[3] ?? '#');   // SDD
        $sheet->setCellValue('E' . $rowCount, $row[4] ?? '');    // Remarks
        $sheet->setCellValue('F' . $rowCount, $row[5] ?? '0');   // Marks
        $rowCount++;
    }

    // 4. Clean .xlsx File Name aur Download logic
    $fileName = "All_Students_Master_Marksheet_" . now()->format('Y-m-d_H-i') . ".xlsx";
    $writer = new Xlsx($spreadsheet);

    return response()->stream(
        function () use ($writer) {
            $writer->save('php://output');
        },
        200,
        [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]
    );
}
}