@extends('layouts.app', ['title' => 'Dashboard | SPIRUTWIN'])
@section('content')
<div class="card"><h1>Dashboard Admin SPIRUTWIN</h1><p>Kelola data petani, penugasan kolam, dan pemantauan sistem dari dashboard ini.</p><p><a class="button" href="{{ route('admin.petani.index') }}">Kelola Data Petani</a></p></div>
@endsection
