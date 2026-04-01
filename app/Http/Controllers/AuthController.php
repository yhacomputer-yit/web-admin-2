<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    // direct login page
    public function login(){
        return Inertia::render('Login');
    }

    // process login for all users (admin and regular users)
    public function loginProcess(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check if user is admin
            if(Auth::user()->role == 'admin'){
                $redirectUrl = route('admin.home');
            } elseif(Auth::user()->role == 'user'){
                $redirectUrl = route('user.home');
            } else {
                // If role is not recognized, logout and show error
                Auth::logout();
                if ($request->expectsJson()) {
                    return response()->json(['errors' => ['email' => 'Invalid user role.']], 422);
                }
                return back()->withErrors([
                    'email' => 'Invalid user role.',
                ]);
            }

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json(['redirect' => $redirectUrl]);
            }

            // Regular form submission redirect
            return redirect($redirectUrl);
        }

        // Return JSON errors for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['errors' => ['email' => 'The provided credentials do not match our records.']], 422);
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
        return Inertia::render('Register');
    }

    // process registration for all users
    public function registerProcess(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user', // Default role for new registrations
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['redirect' => route('user.home')]);
        }

        return redirect()->route('user.home');
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
