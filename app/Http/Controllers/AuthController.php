<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showMainPage()
    {
        return view('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email is not registered!'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password!'])->withInput();
        }

        session([
            'email' => $user->email,
            'name' => $user->name,
            'lastname' => $user->lastname,
            'photo' => $user->photo,
            'account_type' => $user->account_type,
        ]);

        /*if ($user->account_type === "1" || $user->account_type === "2") {
            return redirect('dashboard')->with('success', 'Login successful!');
        } else {
            return redirect('login')->with('acc_banned', true);
        }*/

        Auth::login($user);

        return redirect('dashboard')->with('success', 'Login successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();                      // Logs the user out
        $request->session()->invalidate();  // Invalidate the session
        $request->session()->regenerateToken(); // Prevent CSRF reuse

        return redirect('/login');
    }
}
