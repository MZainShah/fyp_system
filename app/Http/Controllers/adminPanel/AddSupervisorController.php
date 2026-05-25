<?php

namespace App\Http\Controllers\adminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GoogleDriveService;

class AddSupervisorController extends AdminBaseController
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        parent::__construct();
        $this->driveService = $driveService;
    }

    public function create()
    {
        return view('adminPanel.addSupervisor');
    }

    public function store(Request $request, GoogleDriveService $drive)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'role'  => 'required',
        ]);

        // ✅ Check unique email
        if ($drive->emailExists($request->email)) {
            return back()
                ->withInput()
                ->with('error', 'This email already exists.');
        }

        $drive->addSupervisor(
            $request->name,
            $request->email,
            $request->role
        );

        return back()->with('success', 'Supervisor Added Successfully');
    }

    public function edit($id)
    {
        $supervisors = $this->driveService->getAllSupervisors();

        $supervisor = collect($supervisors)
            ->firstWhere('id', $id);

        if (!$supervisor) {
            return back()->with('error', 'Supervisor not found');
        }

        return view('adminPanel.editSupervisor', compact('supervisor'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name'  => 'required',
    //         'email' => 'required',
    //         'role'  => 'required',
    //     ]);

    //     $supervisors = $this->driveService->getAllSupervisors();

    //     $supervisor = collect($supervisors)
    //         ->firstWhere('id', $id);

    //     if (!$supervisor) {
    //         return back()->with('error', 'Supervisor not found');
    //     }

    //     $this->driveService->updateSupervisor(
    //         $supervisor['row'],
    //         $id,
    //         $request->name,
    //         $request->email,
    //         $request->role
    //     );

    //     return redirect()->route('admin.supervisors')
    //         ->with('success', 'Supervisor updated successfully');
    // }

    public function update(Request $request, $id, GoogleDriveService $drive)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'role'  => 'required',
        ]);

        $supervisors = $drive->getAllSupervisors();

        $supervisor = collect($supervisors)
            ->firstWhere('id', $id);

        if (!$supervisor) {
            return back()->with('error', 'Supervisor not found');
        }

        // ✅ Unique check ignoring current ID
        if ($drive->emailExists($request->email, $id)) {
            return back()
                ->withInput()
                ->with('error', 'This email already exists.');
        }

        $drive->updateSupervisor(
            $supervisor['row'],
            $id,
            $request->name,
            $request->email,
            $request->role
        );

        return redirect()->route('admin.supervisors')
            ->with('success', 'Supervisor updated successfully');
    }

    public function destroy($id, GoogleDriveService $drive)
    {
        $supervisors = $drive->getAllSupervisors();

        $supervisor = collect($supervisors)
            ->firstWhere('id', $id);

        if (!$supervisor) {
            return back()->with('error', 'Supervisor not found');
        }

        $drive->deleteSupervisor($supervisor['row']);

        return back()->with('success', 'Supervisor deleted successfully');
    }
}
