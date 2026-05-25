<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;

class AuthController extends Controller
{
    protected $drive;

    public function __construct(GoogleDriveService $drive)
    {
        $this->drive = $drive;
    }

    // Login page dikhane ke liye
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // 1. Static Admin Login (IUB Admin)
        if ($email == 'admin@iub.edu.pk' && $password == 'admin123') {
            session([
                'user_role' => 'admin', 
                'user_email' => $email,
                'user_name' => 'Main Admin'
            ]);
            return redirect()->route('admin.dashboard');
        }

        // 2. Drive se Users ka data check karna
        $file = $this->drive->findFile('users.json');
        
        if (!$file) {
            return back()->with('error', 'No users found on Drive. Please contact Admin!');
        }

        $users = $this->drive->getFileData($file['id']);
        
        // Users list mein dhoondna
        foreach ($users as $user) {
            if ($user['email'] == $email && $user['password'] == $password) {
                session([
                    'user_role' => $user['role'], 
                    'user_email' => $user['email'],
                    'user_name' => $user['name']
                ]);

                // Role ke mutabiq redirect karna
                if ($user['role'] == 'supervisor') {
                    return redirect()->route('supervisor.dashboard');
                } elseif ($user['role'] == 'student') {
                    return redirect()->route('student.dashboard');
                }
            }
        }

        return back()->with('error', 'Invalid Email or Password, Sir!');
    }

    // Logout karne ke liye
    public function logout()
    {
        session()->forget(['user_role', 'user_email', 'user_name']);
        return redirect('/')->with('success', 'Logged out successfully');
    }
}