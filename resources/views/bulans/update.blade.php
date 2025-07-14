@extends('template.master')

@section('dashboard')
@if (session()->has('fails'))
    {{ 'error' }}
@endif

<form class='flex flex-col w-100' action={{ route('bulan.update', ['id'=>$data->id]) }} method="POST">
    @csrf
    <div class="flex flex-row gap-5 w-dvh">
        <div class="flex flex-col w-100">
            <div class="mb-3">
                <label for="umur">Umur: </label>
                <input type="text" name="umur" id="umur" placeholder="Enter input...." value="{{ $data->umur }}">
                @error('umur')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="kelas">Kelas: </label>
                <select name="mood" id="mood">
                    <option @if($data->kelas == '10') {{'selected'}} @endif value="10">Kelas 10</option>
                    <option @if($data->kelas == '11') {{'selected'}} @endif value="11">Kelas 11</option>
                    <option @if($data->kelas == '12') {{'selected'}} @endif value="12">Kelas 12</option>
                </select>
                @error('kelas')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tanggal_mulai">Tanggal Mulai: </label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" placeholder="Enter input...." value={{ $data->tanggal_mulai }}>
                @error('tanggal_mulai')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="durasi_siklus">Durasi Siklus: </label>
                <input type="text" name="durasi_siklus" id="durasi_siklus" placeholder="Enter input...." value={{ $data->durasi_siklus }}>
                @error('durasi_siklus')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="durasi_haid">Durasi Haid: </label>
                <input type="text" name="durasi_haid" id="durasi_haid" placeholder="Enter input...." value={{ $data->durasi_haid }}>
                @error('durasi_haid')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="suhu_tubuh">Suhu Tubuh: </label>
                <input type="text" name="suhu_tubuh" id="suhu_tubuh" placeholder="Enter input...." value={{ $data->suhu_tubuh }}>
                @error('suhu_tubuh')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex flex-col w-100">
            <div class="mb-3">
                <label for="mood">Mood: </label>
                <select name="mood" id="mood">
                    <option @if ($data->mood == 'Murung') {{ 'selected' }} @endif value="Murung">Murung</option>
                    <option @if ($data->mood == 'Stabil') {{ 'selected' }} @endif value="Stabil">Stabil</option>
                    <option @if ($data->mood == 'Sensitif') {{ 'selected' }} @endif value="Sensitif">Sensitif</option>
                </select>
                @error('mood')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nyeri">Nyeri: </label>
                <select name="nyeri" id="nyeri">
                    <option @if ($data->nyeri == 'Tidak') {{ 'selected' }} @endif value="Tidak">Tidak</option>
                    <option @if ($data->nyeri == 'Ringan') {{ 'selected' }} @endif value="Ringan">Ringan</option>
                    <option @if ($data->nyeri == 'Sedang') {{ 'selected' }} @endif value="Sedang">Sedang</option>
                    <option @if ($data->nyeri == 'Berat') {{ 'selected' }} @endif value="Berat">Berat</option>
                </select>
                @error('nyeri')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="aktivitas_fisik">Aktivitas Fisik: </label>
                <select name="aktivitas_fisik" id="aktivitas_fisik">
                    <option @if ($data->aktivitas_fisik == 'Tidak') {{ 'selected' }} @endif value="Tidak">Tidak</option>
                    <option @if ($data->aktivitas_fisik == 'Rendah') {{ 'selected' }} @endif value="Ringan">Ringan</option>
                    <option @if ($data->aktivitas_fisik == 'Sedang') {{ 'selected' }} @endif value="Sedang">Sedang</option>
                    <option @if ($data->aktivitas_fisik == 'Tinggi') {{ 'selected' }} @endif value="Tinggi">Tinggi</option>
                </select>
                @error('aktivitas_fisik')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="stress">Stress: </label>
                <select name="stress" id="stress">
                    <option @if ($data->stress == 'Tidak') {{ 'selected' }} @endif value="Tidak">Tidak</option>
                    <option @if ($data->stress == 'Ringan') {{ 'selected' }} @endif value="Ringan">Ringan</option>
                    <option @if ($data->stress == 'Sedang') {{ 'selected' }} @endif value="Sedang">Sedang</option>
                    <option @if ($data->stress == 'Berat') {{ 'selected' }} @endif value="Berat">Berat</option>
                </select>
                @error('stress')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="obat">Obat: </label>
                <select name="obat" id="obat">
                    <option @if ($data->obat == 'Ya') {{ 'selected' }} @endif value="Ya">Ya</option>
                    <option @if ($data->obat == 'Tidak') {{ 'selected' }} @endif value="Tidak">Tidak</option>
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