<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // phone_number diwajibkan, murni angka, dan wajib unik di tabel users
            'phone_number' => ['required', 'numeric', 'unique:users,phone_number'],
            // email diubah jadi nullable dan TIDAK LAGI unik
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            // Pesan UX bersahabat ketika nomor HP duplikat
            'phone_number.unique' => 'Nomor HP ini sudah terdaftar. Jika ini nomor kamu, silakan Masuk atau gunakan fitur Lupa Kata Sandi.',
            'phone_number.numeric' => 'Nomor HP hanya boleh berisi angka.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'], // Simpan nomor HP
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('belajar'));
    }
}