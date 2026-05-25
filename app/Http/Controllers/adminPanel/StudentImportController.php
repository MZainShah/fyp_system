<?php

namespace App\Http\Controllers\adminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class StudentImportController extends AdminBaseController
{
    private $sheetId = "1XqZ0Yi28YMxL9oj8-mAPzYgVHAp0a_egzGoDFrcvbpQ";

    public function index()
    {
        return view('adminPanel.studentImport');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel');

        // Load Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Get existing students to prevent duplicates
        $existingRolls = $this->getExistingRollNumbers();

        $students = [];
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {

            if ($index === 0) continue; // skip header

            $roll = strtolower(trim($row[0] ?? ''));
            $name = trim($row[1] ?? '');

            if (!$roll || !$name) {
                $skipped++;
                continue;
            }

            // Skip duplicate roll numbers
            if (in_array($roll, $existingRolls)) {
                $skipped++;
                continue;
            }

            $email = $roll . '@iub.edu.pk';

            $students[] = [
                Str::uuid()->toString(),
                $name,
                $roll,
                $email,
                'student',
                Carbon::now()->format('Y-m-d')
            ];

            $imported++;
        }

        if (!empty($students)) {
            $this->appendStudents($students);
        }

        return redirect()->route('admin.students.list')
            ->with('success', "Import Completed. Imported: {$imported}, Skipped: {$skipped}");
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ]);

        return $response->json()['access_token'];
    }

    private function getExistingRollNumbers()
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F"
        );

        if (!$response->successful()) {
            return [];
        }

        $values = $response->json()['values'] ?? [];

        $rolls = [];

        foreach ($values as $index => $row) {
            if ($index === 0) continue; // skip header

            if (!empty($row[2])) {
                $rolls[] = strtolower(trim($row[2]));
            }
        }

        return $rolls;
    }

    private function appendStudents($students)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->post(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F:append?valueInputOption=RAW",
            [
                'values' => $students
            ]
        );

        if (!$response->successful()) {
            dd($response->status(), $response->body());
        }

        return true;
    }

    public function studentsList()
    {
        $students = $this->getAllStudents();
        return view('adminPanel.studentsList', compact('students'));
    }

    // private function getAllStudents()
    // {
    //     $token = $this->getAccessToken();

    //     $response = \Illuminate\Support\Facades\Http::withToken($token)->get(
    //         "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F"
    //     );

    //     if (!$response->successful()) {
    //         return [];
    //     }

    //     $values = $response->json()['values'] ?? [];

    //     $students = [];

    //     foreach ($values as $index => $row) {

    //         if ($index === 0) continue; // skip header

    //         $students[] = [
    //             'name'  => $row[1] ?? '',
    //             'roll'  => strtoupper($row[2] ?? ''),
    //             'email' => strtolower($row[3] ?? ''),
    //             'role'  => $row[4] ?? '',
    //         ];
    //     }

    //     return $students;
    // }

    private function getAllStudents()
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F"
        );

        if (!$response->successful()) {
            return [];
        }

        $values = $response->json()['values'] ?? [];

        $students = [];

        foreach ($values as $index => $row) {

            if ($index === 0) continue; // skip header

            $students[] = [
                'id'    => $row[0] ?? '',   // 👈 UUID
                'name'  => $row[1] ?? '',
                'roll'  => strtoupper($row[2] ?? ''),
                'email' => strtolower($row[3] ?? ''),
                'role'  => $row[4] ?? '',
            ];
        }

        return $students;
    }

    public function deleteStudent($id)
    {
        $token = $this->getAccessToken();

        // Get sheet data to find row index
        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F"
        );

        if (!$response->successful()) {
            return back()->with('error', 'Failed to fetch sheet data.');
        }

        $values = $response->json()['values'] ?? [];

        $rowIndex = null;

        foreach ($values as $index => $row) {

            if ($index === 0) continue; // skip header

            if (($row[0] ?? '') === $id) {
                $rowIndex = $index; // zero-based including header
                break;
            }
        }

        if ($rowIndex === null) {
            return back()->with('error', 'Student not found.');
        }

        // Delete row using batchUpdate
        $deleteResponse = Http::withToken($token)->post(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}:batchUpdate",
            [
                "requests" => [
                    [
                        "deleteDimension" => [
                            "range" => [
                                "sheetId" => 0,  // Usually first sheet = 0
                                "dimension" => "ROWS",
                                "startIndex" => $rowIndex,
                                "endIndex" => $rowIndex + 1
                            ]
                        ]
                    ]
                ]
            ]
        );

        if (!$deleteResponse->successful()) {
            return back()->with('error', 'Failed to delete student.');
        }

        return redirect()->route('admin.students.list')
            ->with('success', 'Student deleted successfully.');
    }

    public function editStudent($id)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F"
        );

        if (!$response->successful()) {
            return back()->with('error', 'Failed to fetch data.');
        }

        $values = $response->json()['values'] ?? [];

        foreach ($values as $index => $row) {

            if ($index === 0) continue;

            if (($row[0] ?? '') === $id) {

                $student = [
                    'rowIndex' => $index, // needed for update
                    'id'    => $row[0],
                    'name'  => $row[1],
                    'roll'  => $row[2],
                    'email' => $row[3],
                    'role'  => $row[4],
                ];

                return view('adminPanel.editStudent', compact('student'));
            }
        }

        return back()->with('error', 'Student not found.');
    }

    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'roll' => 'required',
            'role' => 'required',
            'rowIndex' => 'required'
        ]);

        $token = $this->getAccessToken();

        $roll = strtolower(trim($request->roll));
        $email = $roll . '@iub.edu.pk';

        $rowIndex = (int) $request->rowIndex;

$students = $this->getAllStudents();

foreach ($students as $student) {

    // ignore current student
    if ($student['id'] == $id) {
        continue;
    }

    if (strtolower($student['roll']) == $roll) {
        return back()->with('error', 'Roll number already exists.');
    }

    if (strtolower($student['email']) == $email) {
        return back()->with('error', 'Email already exists.');
    }
}

        $updatedRow = [
            $id,
            $request->name,
            $roll,
            $email,
            $request->role,
            Carbon::now()->format('Y-m-d')
        ];

        $response = Http::withToken($token)->put(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A" . ($rowIndex + 1) . ":F" . ($rowIndex + 1) . "?valueInputOption=RAW",
            [
                'values' => [$updatedRow]
            ]
        );

        if (!$response->successful()) {
            return back()->with('error', 'Failed to update student.');
        }

        return redirect()->route('admin.students.list')
            ->with('success', 'Student updated successfully.');
    }

    public function create()
    {
        return view('adminPanel.addStudent');
    }

    private function getAllStudentsRaw()
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/Sheet1!A:F"
        );

        if (!$response->successful()) {
            return [];
        }

        $values = $response->json()['values'] ?? [];

        $students = [];

        foreach ($values as $index => $row) {

            if ($index === 0) continue;

            $students[] = [
                'roll'  => $row[2] ?? '',
                'email' => $row[3] ?? '',
            ];
        }

        return $students;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'roll' => 'required'
        ]);

        $roll = strtolower(trim($request->roll));
        $email = $roll . '@iub.edu.pk';

        $existingStudents = $this->getAllStudentsRaw();

        foreach ($existingStudents as $student) {

            if (strtolower($student['roll']) === $roll) {
                return back()->with('error', 'Roll number already exists.');
            }

            if (strtolower($student['email']) === $email) {
                return back()->with('error', 'Email already exists.');
            }
        }

        $newStudent = [
            Str::uuid()->toString(),
            $request->name,
            $roll,
            $email,
            'student',
            Carbon::now()->format('Y-m-d')
        ];

        $this->appendStudents([$newStudent]);

        return redirect('/admin/students/list')
            ->with('success', 'Student added successfully.');
    }
}
