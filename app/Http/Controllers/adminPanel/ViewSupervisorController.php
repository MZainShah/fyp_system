<?php

namespace App\Http\Controllers\adminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GoogleDriveService;

class ViewSupervisorController extends AdminBaseController
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        parent::__construct();
        $this->driveService = $driveService;
    }

    public function index()
    {
        $supervisors = $this->driveService->getAllSupervisors();
        return view('adminPanel.viewSupervisors', compact('supervisors'));
    }
}