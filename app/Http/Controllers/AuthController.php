<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Parāda reģistrācijas formu
    public function showRegister()
    {
        return view('auth.register');
    }

    // Parāda pieteikšanās formu
    public function showLogin()
    {
        return view('auth.login');
    }

    // Apstrādā reģistrācijas formu
    public function register(Request $request)
    {
        // Validācija
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Izveido jaunu lietotāju (pēc noklusējuma role = 'user')
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user', // Automātiski piešķir 'user' lomu
        ]);

        // Automātiski pieteic lietotāju
        Auth::login($user);

        // Novirza uz recepšu sarakstu
        return redirect()->route('recipes.index')->with('success', 'Registration successful!');
    }

    // Apstrādā pieteikšanās formu
    public function login(Request $request)
    {
        // Validācija
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mēģina pieteikt lietotāju
        if (Auth::attempt($credentials)) {
            // Reģenerē sesijas ID (drošībai)
            $request->session()->regenerate();
            
            // Novirza uz recepšu sarakstu
            return redirect()->intended(route('recipes.index'))->with('success', 'Logged in successfully!');
        }

        // Ja autentifikācija neizdodas
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Izrakstīšanās
    public function logout(Request $request)
    {
        // Izraksta lietotāju
        Auth::logout();
        
        // Iznīcina sesiju
        $request->session()->invalidate();
        
        // Reģenerē CSRF tokenu
        $request->session()->regenerateToken();
        
        // Novirza uz pieteikšanās lapu
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}