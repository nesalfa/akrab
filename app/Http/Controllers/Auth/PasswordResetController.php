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

class PasswordResetController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 15;
    private const RESEND_COOLDOWN_SECONDS = 60;

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'numeric'], // Cari pakai nomor HP sekarang
        ]);

        $user = User::where('phone_number', $validated['phone_number'])->where('role', 'user')->first();

        // Cek: Apakah user ada? DAN apakah dia mendaftarkan email pendamping?
        if ($user && $user->email) {
            // Kita pinjam kolom 'email' di tabel password_reset_tokens untuk menyimpan nomor HP
            // Tujuannya agar token tidak tertimpa oleh kakak/adik yang emailnya sama.
            $existing = DB::table('password_reset_tokens')->where('email', $user->phone_number)->first();

            $secondsSinceLastSent = $existing
                ? now()->diffInSeconds(\Illuminate\Support\Carbon::parse($existing->created_at), false)
                : null;

            $stillInCooldown = $secondsSinceLastSent !== null
                && $secondsSinceLastSent >= 0
                && $secondsSinceLastSent < self::RESEND_COOLDOWN_SECONDS;

            if ($stillInCooldown) {
                return redirect()
                    ->route('password.reset.form', ['phone_number' => $validated['phone_number']])
                    ->with('status', 'Kode baru saja dikirim. Coba lagi sesaat lagi, ya.');
            }

            $otp = (string) random_int(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->phone_number], // Simpan pakai no HP agar 100% unik per anak
                ['token' => Hash::make($otp), 'created_at' => now()]
            );

            // Kirim OTP tetap meluncur ke emailnya
            $user->notify(new OtpResetPasswordNotification($otp, self::OTP_EXPIRY_MINUTES));
        }

        // UX Security: Pesan sukses selalu sama entah nomor HP-nya valid, ada email, atau kosong
        return redirect()
            ->route('password.reset.form', ['phone_number' => $validated['phone_number']])
            ->with('status', 'Kalau nomor HP ini terdaftar dan memiliki Email Pendamping, kode OTP sudah kami kirim. Cek email (termasuk folder spam).');
    }

    public function showResetForm(Request $request): View
    {
        return view('auth.reset-password', [
            'phone_number' => $request->query('phone_number', ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'numeric'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Cari token berdasarkan nomor HP yang dipinjamkan ke kolom email
        $record = DB::table('password_reset_tokens')->where('email', $validated['phone_number'])->first();

        if (!$record || !Hash::check($validated['otp'], $record->token)) {
            return back()
                ->withErrors(['otp' => 'Kode OTP salah atau sudah tidak berlaku.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if (now()->diffInMinutes($record->created_at) > self::OTP_EXPIRY_MINUTES) {
            DB::table('password_reset_tokens')->where('email', $validated['phone_number'])->delete();

            return back()
                ->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::where('phone_number', $validated['phone_number'])->where('role', 'user')->first();

        if (!$user) {
            return back()->withErrors(['phone_number' => 'Akun tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        DB::table('password_reset_tokens')->where('email', $validated['phone_number'])->delete();

        return redirect()
            ->route('login')
            ->with('status', 'Kata sandi berhasil diganti. Silakan masuk dengan kata sandi baru.');
    }
}