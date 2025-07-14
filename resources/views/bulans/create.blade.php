@extends('template.master')

@section('dashboard')
@if (session()->has('fails'))
    {{ 'error' }}
@endif

<form class='flex flex-col w-100' action={{ route('bulan.store') }} method="POST">
    @csrf
    <div class="flex flex-row gap-5 w-dvh">
        <div class="flex flex-col w-100">w
            <div class="mb-3">
                <label for="umur">Umur: </label>
                <input type="text" name="umur" id="umur" placeholder="Enter input....">
                @error('umur')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="semester">Semester: </label>
                <select name="semester" id="semester">
                    <option disabled selected>--Pilih Salah Satu</option>
                    @for ($i = 1; $i < 7; $i++)
                    <option value="{{ $i }}">Semester {{ $i }}</option>
                    @endfor
                </select>
                @error('kelas')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tanggal_mulai">Tanggal Mulai: </label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" placeholder="Enter input....">
                @error('tanggal_mulai')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="durasi_siklus">Durasi Siklus: </label>
                <input type="text" name="durasi_siklus" id="durasi_siklus" placeholder="Enter input....">
                @error('durasi_siklus')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="durasi_haid">Durasi Haid: </label>
                <input type="text" name="durasi_haid" id="durasi_haid" placeholder="Enter input....">
                @error('durasi_haid')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="suhu_tubuh">Suhu Tubuh: </label>
                <input type="text" name="suhu_tubuh" id="suhu_tubuh" placeholder="Enter input....">
                @error('suhu_tubuh')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex flex-col w-100">
    
            <div class="mb-3">
                <label for="masalah_kesehatan">Masalah Kesehatan: </label>
                <select name="masalah_kesehatan" id="masalah_kesehatan">
                    <option disabled selected>--Pilih Salah Satu</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                </select>
                @error('mood')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="rokok">Rokok: </label>
                <select name="rokok" id="rokok">
                    <option disabled selected>--Pilih Salah Satu</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                </select>
                @error('rokok')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="aktivitas_fisik">Aktivitas Fisik: </label>
                <select name="aktivitas_fisik" id="aktivitas_fisik">
                    <option disabled selected>--Pilih Salah Satu</option>
                    <option value="Tidak">Tidak</option>
                    <option value="Ringan">Ringan</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Berat">Berat</option>
                </select>
                @error('aktivitas_fisik')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="stress">Stress: </label>
                <select name="stress" id="stress">
                    <option disabled selected>--Pilih Salah Satu</option>
                    <option value="Tidak">Tidak</option>
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                </select>
                @error('stress')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="obat">Obat: </label>
                <select name="obat" id="obat">
                    <option disabled selected>--Pilih Salah Satu</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                </select>
                @error('obat')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="flex gap-5 mt-3">
        <button class="btn bg-(--blue-dark-mode-color) text-white" type="submit">Submit</button>
        <a href={{ route('bulan.index') }} class="btn bg-(--red-dark-mode-color) text-white">Cancel</a>
    </div>
</form>

@endsection