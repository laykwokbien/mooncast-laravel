@extends('template.master')

@section('dashboard')
<div class="flex">
    <form class='flex flex-col w-100' action="{{ route('realtime.update', $data->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nis">Nama siswa: </label>
            <select name="nis" id="nis">
                <option selected value="{{ $data->siswa->nis }}">{{ $data->siswa->nama_siswa }}</option>
            </select>
            @error('nis')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="umur">Umur: </label>
            <input type="text" name="umur" id="umur" placeholder="Masukkan...." value="{{ old('umur', $data->umur) }}">
            @error('umur')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="semester">Semester: </label>
            <select name="semester" id="semester">
                @for ($i = 1; $i < 7; $i++)
                <option @if (old('semester', $data->semester) == $i) {{ 'selected' }} @endif value="{{ $i }}">Semester {{ $i }}</option>
                @endfor
            </select>
            @error('semester')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tanggal_mulai">Tanggal Mulai: </label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" placeholder="Masukkan...." value="{{ old('tanggal_mulai', $data->tanggal_mulai) }}">
            @error('tanggal_mulai')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="suhu_tubuh">Suhu Tubuh: </label>
            <input type="text" name="suhu_tubuh" id="suhu_tubuh" placeholder="Masukkan...." value="{{ old('suhu_tubuh', $data->suhu_tubuh) }}">
            @error('suhu_tubuh')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="masalah_kesehatan">Masalah Kesehatan: </label>
            <select name="masalah_kesehatan" id="masalah_kesehatan">
                <option @if (old('masalah_kesehatan', $data->masalah_kesehatan) == "Ya") {{'selected'}}  @endif value="Ya">Ya</option>
                <option @if (old('masalah_kesehatan', $data->masalah_kesehatan) == "Tidak") {{'selected'}}  @endif value="Tidak">Tidak</option>
            </select>
            @error('masalah_kesehatan')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="rokok">Rokok: </label>
            <select name="rokok" id="rokok">
                <option @if (old('rokok', $data->rokok) == "Ya") {{'selected'}} @endif value="Ya">Ya</option>
                <option @if (old('rokok', $data->rokok) == "Tidak") {{'selected'}} @endif value="Tidak">Tidak</option>
            </select>
            @error('rokok')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="aktivitas_fisik">Aktivitas Fisik: </label>
            <select name="aktivitas_fisik" id="aktivitas_fisik">
                <option @if (old('aktivitas_fisik', $data->aktivitas_fisik) == "Ringan") {{ 'selected' }} @endif value="Ringan">Ringan</option>
                <option @if (old('aktivitas_fisik', $data->aktivitas_fisik) == "Sedang") {{ 'selected' }} @endif value="Sedang">Sedang</option>
                <option @if (old('aktivitas_fisik', $data->aktivitas_fisik) == "Berat") {{ 'selected' }} @endif value="Berat">Berat</option>
            </select>
            @error('aktivitas_fisik')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="stress">Stress: </label>
            <select name="stress" id="stress">
                <option @if (old('stress', $data->stress) == "Rendah") {{ 'selected' }} @endif value="Rendah">Rendah</option>
                <option @if (old('stress', $data->stress) == "Sedang") {{ 'selected' }} @endif value="Sedang">Sedang</option>
                <option @if (old('stress', $data->stress) == "Tinggi") {{ 'selected' }} @endif value="Tinggi">Tinggi</option>
            </select>
            @error('stress')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="obat">Obat: </label>
            <select name="obat" id="obat">
                <option @if (old('obat', $data->obat) == "Ya") {{ 'selected' }} @endif value="Ya">Ya</option>
                <option @if (old('obat', $data->obat) == "Tidak") {{ 'selected' }} @endif value="Tidak">Tidak</option>
            </select>
            @error('obat')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex gap-10">
            <button class="btn bg-(--blue-dark-mode-color) text-white" type="submit">Submit</button>
            <a class="btn bg-(--red-dark-mode-color) text-white" href="{{ route('realtime.data') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection