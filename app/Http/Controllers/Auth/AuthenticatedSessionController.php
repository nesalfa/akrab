<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * SATU form untuk user & admin (portal login-nya tetap sama, sesuai
     * permintaan). Nama field inputnya `login` (bukan `email` atau
     * `username` langsung) karena field ini dobel-fungsi: bisa diisi
     * email (user) ATAU kode staf seperti "P1234" (admin).
     *
     * Logika pembeda role — PERSIS seperti yang diminta:
     * - Isian `login` berformat email valid -> dicoba login sebagai
     *   role 'user', dicocokkan ke kolom `email`.
     * - Isian `login` BUKAN format email (mis. "P1234") -> dicoba login
     *   sebagai role 'admin', dicocokkan ke kolom `username`.
     *
     * Kondisi `role` disertakan LANGSUNG di dalam Auth::attempt(), bukan
     * cuma dicek belakangan setelah login berhasil. Artinya: kalaupun ada
     * baris di tabel users yang emailnya kebetulan cocok tapi role-nya
     * 'admin' (harusnya tidak mungkin terjadi kalau alur pembuatan akun
     * diikuti dengan benar), tetap tidak akan bisa login lewat jalur
     * email — pemisahannya ditegakkan di level query database.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $isEmailFormat = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) !== false;
        $remember = $request->boolean('remember');

        $attempt = $isEmailFormat
            ? Auth::attempt([
                'email' => $credentials['login'],
                'password' => $credentials['password'],
                'role' => 'user',
            ], $remember)
            : Auth::attempt([
                'username' => $credentials['login'],
                'password' => $credentials['password'],
                'role' => 'admin',
            ], $remember);

        if (!$attempt) {
            return back()
                ->withErrors(['login' => 'Username/email atau kata sandi yang kamu masukkan salah.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        return Auth::user()->isAdmin()
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('module.show', 'mengenal-tubuh-kita'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
