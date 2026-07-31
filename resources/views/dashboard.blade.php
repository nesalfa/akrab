@extends('layouts.app')

@section('title', 'Dashboard Admin - AKRAB')

@section('content')
    {{--
    Ini KERANGKA MINIMAL, bukan dashboard admin yang lengkap — tujuannya
    cuma supaya alur "login sebagai admin -> diarahkan ke sini" bisa
    langsung dites dan tidak 404. Isi dashboard sungguhan (kelola
    modul, lihat data quiz_attempts pre/post-test, dst.) belum dibangun
    karena belum diminta secara eksplisit — kabari kalau memang perlu.
    --}}
    <div class="card auth-card shadow-sm p-4 p-md-5">
        <h1 class="h3 fw-bold mb-2" style="color: var(--primary-color);">
            <i class="bi bi-speedometer2" aria-hidden="true"></i> Dashboard Admin
        </h1>
        <p class="mb-4" style="color: var(--text-light);">
            Halo, {{ auth()->user()->name }}. Halaman admin.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-akrab-outline">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
            </button>
        </form>
    </div>
@endsection