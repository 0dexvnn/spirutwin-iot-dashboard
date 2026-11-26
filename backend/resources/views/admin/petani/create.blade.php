@extends('layouts.app', ['title' => 'Tambah Petani | SPIRUTWIN'])
@section('content')
<div class="card"><h1>Tambah Data Petani</h1><form method="post" action="{{ route('admin.petani.store') }}">@csrf
<label>Nama lengkap</label><input name="name" value="{{ old('name') }}" required>@error('name')<div class="error">{{ $message }}</div>@enderror
<label>NIK</label><input name="nik" value="{{ old('nik') }}" required>@error('nik')<div class="error">{{ $message }}</div>@enderror
<label>No. KK</label><input name="no_kk" value="{{ old('no_kk') }}" required>@error('no_kk')<div class="error">{{ $message }}</div>@enderror
<label>Nomor HP</label><input name="phone" value="{{ old('phone') }}" required>@error('phone')<div class="error">{{ $message }}</div>@enderror
<label>Alamat</label><textarea name="address" required>{{ old('address') }}</textarea>@error('address')<div class="error">{{ $message }}</div>@enderror
<label>Kolam yang ditugaskan</label>@foreach($kolams as $kolam)<label class="check"><input type="checkbox" name="kolam_ids[]" value="{{ $kolam->id }}" @checked(in_array($kolam->id, old('kolam_ids', [])))>{{ $kolam->code }} — {{ $kolam->name }}</label>@endforeach @error('kolam_ids')<div class="error">{{ $message }}</div>@enderror
<button type="submit">Simpan data petani</button> <a href="{{ route('admin.petani.index') }}">Batal</a></form></div>
@endsection
