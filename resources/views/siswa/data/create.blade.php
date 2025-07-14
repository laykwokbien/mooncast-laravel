@extends('template.master')

@section('dashboard')
@if (session()->has('fails'))
    {{ 'error' }}
@endif

<form class='flex flex-col w-100' action={{ route('siswa.data.store') }} method="POST">
    @csrf
    <div class="mt-3">
        <label class="w-100" for="nis">NIS: </label>
        <input type="text" name="nis" id="nis" placeholder="Masukkan...."value="{{ old('nis') }}">
    </div>
    @error('nis')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="nama_siswa">Nama Siswa: </label>
        <input type="text" name="nama_siswa" id="nama_siswa" placeholder="Masukkan...."value="{{ old('nama_siswa') }}">
    </div>
    @error('nama_siswa')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="alamat">Alamat: </label>
        <input type="text" name="alamat" id="alamat" placeholder="Masukkan...." value="{{ old('alamat') }}">
    </div>
    @error('alamat')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="tanggal_lahir">Tanggal Lahir: </label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir" placeholder="Masukkan...." value="{{ old('tanggal_lahir') }}">
    </div>
    @error('tanggal_lahir')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="jurusan">Jurusan: </label>
        <select name="jurusan" id="jurusan">
            <option disabled selected>--Pilih Salah Satu</option>
            @foreach ($jurusan as $row)
                <option value={{ $row->id }}>{{ $row->nama_jurusan }}</option>                
            @endforeach
        </select>
    </div>
    @error('jurusan')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="tahun_masuk">Tahun Masuk: </label>
        <input type="text" name="tahun_masuk" id="tahun_masuk" placeholder="Masukkan...." value="{{ old('tahun_masuk') }}">
    </div>
    @error('tahun_masuk')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="tahun_lulus">Tahun Lulus: </label>
        <input type="text" name="tahun_lulus" id="tahun_lulus" placeholder="Optional..." value="{{ old('tahun_lulus') }}">
    </div>
    @error('tahun_lulus')
    <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    </div>
    <div class="flex gap-5 mt-3">
        <button class="btn bg-(--blue-dark-mode-color) text-white" type="submit">Submit</button>
        <a href={{ route('siswa.data.index') }} class="btn bg-(--red-dark-mode-color) text-white">Cancel</a>
    </div>
</form>
@endsection