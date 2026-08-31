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
 * Alur "Lupa Kata Sandi" pakai kode OTP 6 digit lewat email. Cuma
 * berlaku untuk akun role 'user' (admin login pakai kode staf, bukan
 * email, jadi tidak relevan di sini).
 */
class PasswordResetController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 15;

    // Jeda minimal antar permintaan OTP untuk email yang sama — mencegah
    // orang spam klik "Kirim Ulang" (atau spam POST /forgot-password
    // langsung) untuk email-bombing orang lain. Baru ditambahkan sesuai
    // catatan "belum ada rate-limiting" dari sebelumnya.
    private const RESEND_COOLDOWN_SECONDS = 60;

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim (atau kirim ulang) kode OTP ke email. Pesan sukses SAMA baik
     * email ditemukan atau tidak — supaya orang luar tidak bisa
     * menebak-nebak email mana saja yang terdaftar cuma dari respons
     * halaman ini ("user enumeration prevention").
     *
     * Dipakai baik dari form /forgot-password MAUPUN dari tombol
     * "Kirim Ulang Kode" di halaman /reset-password — dua-duanya POST ke
     * route yang sama (route('password.email')).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->where('role', 'user')->first();

        if ($user) {
            $existing = DB::table('password_reset_tokens')->where('email', $user->email)->first();

            // Throttle: kalau permintaan sebelumnya belum lewat 60 detik,
            // JANGAN generate/kirim OTP baru.
            //
            // PERBAIKAN dari versi sebelumnya: dulu pesannya menampilkan
            // ANGKA detik hasil hitungan (mis. "672.892792 detik lagi") —
            // selain kata-katanya bikin bingung, angkanya juga bisa salah
            // total kalau timezone PHP & MySQL beda (diffInSeconds bisa
            // balik negatif besar). Sekarang: (1) pesan tidak lagi
            // menyebut angka detik sama sekali — cukup "coba lagi sesaat
            // lagi", dan (2) hasil hitungan yang negatif/aneh (tanda ada
            // masalah jam) TIDAK dianggap sebagai "masih dalam cooldown"
            // — supaya user tetap bisa minta kode baru walau ada bug jam,
            // daripada terjebak tidak bisa reset password sama sekali.
            $secondsSinceLastSent = $existing
                ? now()->diffInSeconds(\Illuminate\Support\Carbon::parse($existing->created_at), false)
                : null;

            $stillInCooldown = $secondsSinceLastSent !== null
                && $secondsSinceLastSent >= 0
                && $secondsSinceLastSent < self::RESEND_COOLDOWN_SECONDS;

            if ($stillInCooldown) {
                return redirect()
                    ->route('password.reset.form', ['email' => $validated['email']])
                    ->with('status', 'Kode baru saja dikirim. Coba lagi sesaat lagi, ya.');
            }

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
                ->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Klik "Kirim Ulang Kode" di bawah untuk minta kode baru.'])
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