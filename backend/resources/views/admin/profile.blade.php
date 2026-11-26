@extends('layouts.app', ['title' => 'Profil Admin | SPIRUTWIN'])

@section('content')
    <div class="card profile-card">
        <p class="eyebrow">AKUN ADMINISTRATOR</p>
        <h1>Profil Admin</h1>
        <p class="muted">Informasi akun yang sedang digunakan untuk mengelola SPIRUTWIN.</p>
        <dl class="profile-list">
            <div><dt>Nama</dt><dd>{{ $admin->name }}</dd></div>
            <div><dt>Username</dt><dd>{{ $admin->username }}</dd></div>
            <div><dt>Peran</dt><dd><span class="role-badge">Administrator</span></dd></div>
        </dl>
    </div>
@endsection
