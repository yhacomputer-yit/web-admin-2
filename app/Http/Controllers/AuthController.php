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

    // process login: students (username) first, then admin / regular users (email)
    public function loginProcess(Request $request){
        // accept either field name so cached/older login forms keep working
        $request->merge([
            'username' => $request->input('username') ?: $request->input('email'),
        ]);

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $studentRedirect = $this->attemptStudentLogin($request, $credentials);

        if ($studentRedirect !== null) {
            return $studentRedirect;
        }

        if (Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']])) {
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
                    return response()->json(['errors' => ['username' => 'Invalid user role.']], 422);
                }
                return back()->withErrors([
                    'username' => 'Invalid user role.',
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
            return response()->json(['errors' => ['username' => 'The provided credentials do not match our records.']], 422);
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Try the student guard. Returns a response when the login identifier
     * belongs to a student account (success or inactive), null otherwise.
     */
    private function attemptStudentLogin(Request $request, array $credentials)
    {
        $student = \App\Models\Student::where('username', $credentials['username'])->first();

        if (!$student) {
            return null;
        }

        // verify the password before revealing anything about the account
        if (!Auth::guard('student')->attempt([
            'username' => $student->username,
            'password' => $credentials['password'],
        ])) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => ['password' => 'The provided credentials do not match our records.']], 422);
            }
            return back()->withErrors(['password' => 'The provided credentials do not match our records.']);
        }

        if (!$student->isActive()) {
            Auth::guard('student')->logout();

            if ($request->expectsJson()) {
                return response()->json(['errors' => ['username' => 'Your account is inactive. Please contact the admin.']], 403);
            }
            return back()->withErrors(['username' => 'Your account is inactive. Please contact the admin.']);
        }

        $request->session()->regenerate();

        $redirectUrl = route('student.dashboard');

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
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
