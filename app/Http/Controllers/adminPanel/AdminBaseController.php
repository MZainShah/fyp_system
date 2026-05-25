<?php

namespace App\Http\Controllers\adminPanel;

use App\Http\Controllers\Controller;

class AdminBaseController extends Controller
{
    public function __construct()
    {
       if (!session()->has('role') || session('role') !== 'admin') {
            redirect('/admin/login')->send();
            exit;
        }
    }
}