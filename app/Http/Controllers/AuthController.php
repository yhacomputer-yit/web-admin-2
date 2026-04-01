<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // direct login page
    public function login(){
        return view('login');
    }

    // process admin login
    public function loginProcess(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check if user is admin
            if(Auth::user()->role == 'admin'){
                return redirect()->route('admin.home');
            }
            
            // If not admin, logout and redirect back with error
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access denied. Admin privileges required.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // handle logout
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('loginPage');
    }

    // direct register page
    public function register(){
        return view('register');
    }

    public function dashboard(){

        // dd(Auth::user()->toArray());
        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin.home');
        }
        if(Auth::user()->role == 'user'){
            return redirect()->route('user.home');
        }
        return redirect()->route('login');
    }
}
