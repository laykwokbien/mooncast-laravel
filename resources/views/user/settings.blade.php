@extends('template.master')

@section('dashboard')
<form method="POST" class="w-100" action="{{ route('settings.update')}}">
    @csrf
    <div class="mb-3">
        <label for="nama">Nama:</label>
        <input readonly type="text" name="nama" id="nama" value="{{ old('nama', Auth::user()->nama) }}">
        @error('nama')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="email">Email:</label>
        <input readonly type="text" name="email" id="email" value="{{ old('email', Auth::user()->email) }}">
        @error('email')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password">Password:</label>
        <input class="password_input" readonly type="password" name="password" id="password">
        <input readonly class="password_checkbox" type="checkbox"> <span>Show Password</span>
        @error('password')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="confirm_password">Konfirmasi Password:</label>
        <input class="password_input" readonly type="password" name="confirm_password" id="confirm_password">
        <input class="password_checkbox" type="checkbox"> <span>Show Password</span>
        @error('confirm_password')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="old_password">Password Lama: </label>
        <input class="password_input" readonly type="password" name="old_password" id="old_password">
        <input class="password_checkbox" type="checkbox"> <span>Show Password</span>
        @error('old_password')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex gap-5">
        <div id="editBtn" class="btn bg-(--yellow-dark-mode-color) text-white font-normal">Edit</div>
        <button id="submitBtn" disabled class="btn bg-(--blue-dark-mode-color) text-white font-normal" type="submit">Submit</button>
    </div>
</form>
<hr>
@if (Auth::user()->role == 'admin')
    <h1 class="text-xl w-100">Pengaturan Model</h1>
    <form action="{{ route('admin.updateSchedule') }}" method="post" class="flex">
        @csrf
        <div class="flex mr-3" style="justify-content: center; align-items: center;">
            <label class="w-50" for="auto">Auto Traning: </label>
            <select name="auto" id="auto">
                <option @if ($schedule['auto'] == '0') {{ 'selected' }} @endif value="0">Disable</option>
                <option @if ($schedule['auto'] == '1') {{ 'selected' }} @endif value="1">Enable</option>
            </select>
        </div>
        <div class="flex mr-3" style="justify-content: center; align-items: center;">
            <label class="w-50" for="month">Bulan Traning: </label>
            <select name="month" id="month">
                <option @if ($schedule['date'] == '1') {{ 'selected' }} @endif value="1">Januari</option>
                <option @if ($schedule['date'] == '2') {{ 'selected' }} @endif value="2">Februari</option>
                <option @if ($schedule['date'] == '3') {{ 'selected' }} @endif value="3">Maret</option>
                <option @if ($schedule['date'] == '4') {{ 'selected' }} @endif value="4">April</option>
                <option @if ($schedule['date'] == '5') {{ 'selected' }} @endif value="5">Mei</option>
                <option @if ($schedule['date'] == '6') {{ 'selected' }} @endif value="6">Juni</option>
                <option @if ($schedule['date'] == '7') {{ 'selected' }} @endif value="7">Juli</option>
                <option @if ($schedule['date'] == '8') {{ 'selected' }} @endif value="8">Agustus</option>
                <option @if ($schedule['date'] == '9') {{ 'selected' }} @endif value="9">September</option>
                <option @if ($schedule['date'] == '10') {{ 'selected' }} @endif value="10">Oktober</option>
                <option @if ($schedule['date'] == '11') {{ 'selected' }} @endif value="11">November</option>
                <option @if ($schedule['date'] == '12') {{ 'selected' }} @endif value="12">Desember</option>
            </select>
        </div>
        <button type="submit" class="btn bg-(--blue-dark-mode-color) text-white">Ubah</button>
    </form>
    <h1 class="text-xl">Latih Model secara Manual</h1>
    <div class="flex gap-5">
        <a class="w-fit p-2 rounded-md font-bold bg-(--yellow-dark-mode-color)" href="{{ route('model.train') }}">Latih Model</a>
        <a class="w-fit p-2 rounded-md font-bold bg-(--blue-dark-mode-color)" href="{{ route('model.status') }}">Status Model</a>
    </div>
@endif
@endsection