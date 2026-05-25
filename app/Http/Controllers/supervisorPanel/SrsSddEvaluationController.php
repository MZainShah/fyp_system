<?php

namespace App\Http\Controllers\supervisorPanel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SrsSddEvaluationController extends Controller
{
    private $sheetId = "1uhsGY4M_ulsyIU5H_fMU-vs9_oIQ7UKOW3cBC_BrZ2g";

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
        $token = $this->getAccessToken();
        $supervisorName = Session::get('supervisor_name');

        // Puri sheet fetch karein (A to F: Roll, Name, SRS, SDD, Remarks, Marks)
        $resp = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F");
        $rows = $resp->json()['values'] ?? [];

        $documentationList = [];
        // Pehli row header hai isliye slice kiya
        foreach (array_slice($rows, 1) as $row) {
            $documentationList[] = [
                'roll'    => $row[0] ?? 'N/A',
                'name'    => $row[1] ?? 'N/A',
                'srs'     => $row[2] ?? '#',
                'sdd'     => $row[3] ?? '#',
                'remarks' => $row[4] ?? '',
                'marks'   => $row[5] ?? '',
            ];
        }

        return view('supervisorPanel.viewDocumentation', compact('documentationList'));
    }

    public function updateEvaluation(Request $request)
    {
        $token = $this->getAccessToken();
        $roll = trim($request->roll_number);
        
        // Pehle row index dhoondte hain
        $resp = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:A");
        $allRolls = $resp->json()['values'] ?? [];
        
        $rowIndex = null;
        foreach ($allRolls as $index => $row) {
            if (isset($row[0]) && trim($row[0]) == $roll) {
                $rowIndex = $index + 1;
                break;
            }
        }

        if ($rowIndex) {
            // Column E (Remarks) aur Column F (Marks) update karein
            $values = [
                [$request->remarks, $request->marks]
            ];
            
            // Range E to F for that specific row
            $range = "Sheet1!E{$rowIndex}:F{$rowIndex}";

            Http::withToken($token)->put(
                "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/{$range}?valueInputOption=USER_ENTERED",
                ['values' => $values]
            );

            return redirect()->back()->with('success', 'Sir, Remarks and Marks updated successfully!');
        }

        return redirect()->back()->with('error', 'Student record not found.');
    }

    public function downloadMarksSheet()
{
    // 1. Google Access Token hasil karein
    $token = $this->getAccessToken();
    
    // 2. Apni SRS/SDD Sheet ID yahan likhein
    $sheetId = "1uhsGY4M_ulsyIU5H_fMU-vs9_oIQ7UKOW3cBC_BrZ2g"; 
    $range = "Sheet1!A:F"; // A=Roll, B=Name, F=Marks (Aapki sheet ke mutabiq)

    // 3. Google Sheet se data fetch karein
    $response = Http::withToken($token)->get("https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$range}");
    $data = $response->json();
    $rows = $data['values'] ?? [];

    if (empty($rows)) {
        return back()->with('error', 'Sir, sheet mein koi data nahi mila.');
    }

    // 4. Excel Spreadsheet create karein
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers set karein
    $sheet->setCellValue('A1', 'Roll Number');
    $sheet->setCellValue('B1', 'Student Name');
    $sheet->setCellValue('C1', 'Marks');

    // Styling: Header ko bold karein
    $sheet->getStyle('A1:C1')->getFont()->setBold(true);

    // 5. Data fill karein (Looping through sheet rows)
    $rowCount = 2;
    foreach (array_slice($rows, 1) as $row) {
        // Index 0 = Roll, Index 1 = Name, Index 5 = Marks (Column F)
        $sheet->setCellValue('A' . $rowCount, $row[0] ?? '');
        $sheet->setCellValue('B' . $rowCount, $row[1] ?? '');
        $sheet->setCellValue('C' . $rowCount, $row[5] ?? '0');
        $rowCount++;
    }

    // 6. File download ke liye headers
    $fileName = "Marks_Sheet_" . now()->format('Y-m-d_H-i') . ".xlsx";
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