<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class GuestAuthController extends Controller
{
    public function showLogin()
    {
        return view('guest.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'id_number' => 'required|string',
        ]);

        $guest = Guest::where('email', $request->email)
            ->where('id_number', $request->id_number)
            ->first();

        if (!$guest) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // Store guest in session
        Session::put('guest_id', $guest->id);
        Session::put('guest_name', $guest->full_name);

        return redirect()->route('guest.dashboard')
            ->with('success', 'Welcome back, ' . $guest->first_name . '!');
    }

    public function showRegister()
    {
        return view('guest.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests,email',
            'phone' => 'required|string|max:255',
            'id_number' => 'required|string|unique:guests,id_number',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
        ]);

        $guest = Guest::create($validated);

        // Auto-login after registration
        Session::put('guest_id', $guest->id);
        Session::put('guest_name', $guest->full_name);

        return redirect()->route('guest.dashboard')
            ->with('success', 'Account created successfully! Welcome, ' . $guest->first_name . '!');
    }

    public function logout()
    {
        Session::forget('guest_id');
        Session::forget('guest_name');

        return redirect()->route('guest.login')
            ->with('success', 'You have been logged out successfully.');
    }
}

