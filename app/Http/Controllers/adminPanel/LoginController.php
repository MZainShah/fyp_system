<?php

namespace App\Http\Controllers\adminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('adminPanel.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (
            $request->email === env('ADMIN_EMAIL') &&
            $request->password === env('ADMIN_PASSWORD')
        ) {
            Session::put('role', 'admin');
            Session::put('user_email', $request->email);

            return redirect('/admin/dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    // public function logout()
    // {
    //     session()->flush();
        
    //     return redirect('/admin/login');
    // }

    public function logout(Request $request)
{
    // 1. Browser ke andar jitna bhi session data save hai (Role, Name, etc.) usay mukammal clear karein
    $request->session()->flush();

    // 2. Session ID ko completely regenerate karein taake purani session ID se login bypass na ho sake (Session Fixation Security)
    $request->session()->invalidate();

    // 3. CSRF token ko refresh kar dein security ke liye
    $request->session()->regenerateToken();

    // 4. Seedha main Welcome/Landing Screen par navigate karwa dein
    return redirect('/')->with('success', 'Logged out successfully from all sessions!');
}
}
