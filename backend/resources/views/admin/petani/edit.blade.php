@extends('layouts.app', ['title' => 'Edit Petani | SPIRUTWIN'])
@section('content')
<div class="card"><h1>Edit Petani</h1><form method="post" action="{{ route('admin.petani.update', $petani) }}">@csrf @method('PUT')
<label>Nama lengkap</label><input name="name" value="{{ old('name', $petani->name) }}" required>@error('name')<div class="error">{{ $message }}</div>@enderror
<label>NIK</label><input name="nik" value="{{ old('nik', $petani->nik) }}" required>@error('nik')<div class="error">{{ $message }}</div>@enderror
<label>No. KK</label><input name="no_kk" value="{{ old('no_kk', $petani->no_kk) }}" required>@error('no_kk')<div class="error">{{ $message }}</div>@enderror
<label>Nomor HP</label><input name="phone" value="{{ old('phone', $petani->phone) }}" required>@error('phone')<div class="error">{{ $message }}</div>@enderror
<label>Alamat</label><textarea name="address" required>{{ old('address', $petani->address) }}</textarea>@error('address')<div class="error">{{ $message }}</div>@enderror
<label>Kolam yang terhubung</label>@php($selected = old('kolam_ids', $petani->kolams->pluck('id')->all()))@foreach($kolams as $kolam)<label class="check"><input type="checkbox" name="kolam_ids[]" value="{{ $kolam->id }}" @checked(in_array($kolam->id, $selected))>{{ $kolam->code }} — {{ $kolam->name }}</label>@endforeach @error('kolam_ids')<div class="error">{{ $message }}</div>@enderror
<button type="submit">Simpan perubahan</button> <a href="{{ route('admin.petani.index') }}">Batal</a></form></div>
@endsection
