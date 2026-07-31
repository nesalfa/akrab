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

    /**
     * Form registrasi publik SELALU membuat akun role 'user'. Sengaja
     * tidak ada input/opsi untuk memilih role di form — akun admin
     * (mis. P1234) dibuat manual lewat seeder/tim internal, bukan lewat
     * halaman ini. Field cuma 3 sesuai permintaan: nama, email, password
     * (tanpa konfirmasi password — kalau mau ditambah field
     * "Ulangi Kata Sandi" untuk mencegah typo, tinggal bilang).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // intended(), bukan redirect ke home langsung — supaya kalau tadinya
        // user diarahkan ke sini gara-gara klik card modul saat masih tamu
        // (lihat middleware 'auth' di route module.show), setelah daftar dia
        // balik ke modul yang memang mau dia buka, bukan cuma ke beranda.
        return redirect()->intended(route('module.show', 'mengenal-tubuh-kita'));
    }
}