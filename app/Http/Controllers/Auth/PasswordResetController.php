<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OtpResetPasswordNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Alur "Lupa Kata Sandi" pakai kode OTP 6 digit lewat email — BUKAN link
 * reset seperti bawaan Laravel. Cuma berlaku untuk akun role 'user'
 * (admin login pakai kode staf, bukan email, jadi tidak relevan di sini).
 *
 * Memakai tabel `password_reset_tokens` BAWAAN Laravel (biasanya sudah
 * ada dari migration default users/password_reset_tokens/sessions) —
 * kolom `token`-nya diisi HASH dari kode OTP 6 digit, bukan token
 * panjang seperti biasanya. Kalau tabel ini ternyata belum ada di
 * project-mu, kabari saya, saya buatkan migration-nya (guarded dengan
 * Schema::hasTable supaya tidak bentrok kalau ternyata sudah ada).
 */
class PasswordResetController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 15;

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim kode OTP ke email. Pesan sukses SAMA baik email ditemukan
     * atau tidak — supaya orang luar tidak bisa menebak-nebak email mana
     * saja yang terdaftar cuma dari respons halaman ini (praktik
     * keamanan standar, "user enumeration prevention").
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->where('role', 'user')->first();

        if ($user) {
            $otp = (string) random_int(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($otp), 'created_at' => now()]
            );

            $user->notify(new OtpResetPasswordNotification($otp, self::OTP_EXPIRY_MINUTES));
        }

        return redirect()
            ->route('password.reset.form', ['email' => $validated['email']])
            ->with('status', 'Kalau email itu terdaftar, kode OTP sudah kami kirim. Cek email kamu (termasuk folder spam).');
    }

    public function showResetForm(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Verifikasi OTP + ganti password. Token OTP dihapus setelah dipakai
     * (sekali pakai) ATAU setelah kedaluwarsa, supaya tidak bisa dipakai
     * ulang.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();

        if (!$record || !Hash::check($validated['otp'], $record->token)) {
            return back()
                ->withErrors(['otp' => 'Kode OTP salah atau sudah tidak berlaku.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if (now()->diffInMinutes($record->created_at) > self::OTP_EXPIRY_MINUTES) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            return back()
                ->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Minta kode baru.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::where('email', $validated['email'])->where('role', 'user')->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()
            ->route('login')
            ->with('status', 'Kata sandi berhasil diganti. Silakan masuk dengan kata sandi baru.');
    }
}
