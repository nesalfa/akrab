<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Total modul keseluruhan di aplikasi AKRAB
        $totalModules = 15;

        // Hitung berapa modul unik yang sudah dikerjakan oleh user ini di tabel quiz_attempts
        $completedModules = DB::table('quiz_attempts')
            ->where('user_id', $user->id)
            ->distinct('module_id')
            ->count('module_id');

        // Pastikan jumlah modul selesai tidak melebihi total modul maksimal
        if ($completedModules > $totalModules) {
            $completedModules = $totalModules;
        }

        $progressPercentage = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

        return view('profile.edit', [
            'user' => $user,
            'totalModules' => $totalModules,
            'completedModules' => $completedModules,
            'progressPercentage' => $progressPercentage,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone_number')->ignore($user->id)
            ],
            'email' => ['nullable', 'string', 'email', 'max:255'],
        ], [
            'phone_number.unique' => 'Nomor HP ini sudah digunakan oleh akun lain.',
            'phone_number.numeric' => 'Nomor HP hanya boleh berisi angka.',
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }
}