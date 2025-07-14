@extends('template.master')

@section('dashboard')
@if (session()->has('fails'))
    {{ 'error' }}
@endif

<form class='flex flex-col w-100' action={{ route('siswa.akun.update', ['id'=>$data->id]) }} method="POST">
    @csrf
    <div class="mt-3">
        <label class="w-100" for="nama">Nama: </label>
        <input type="text" name="nama" id="nama" placeholder="Enter input...." value={{ old('nama',$data->nama) }}>
    </div>
    @error('nama')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    @csrf
    <div class="mt-3">
        <label class="w-100" for="email">email: </label>
        <input type="email" name="email" id="email" placeholder="Enter input...." value={{ old('email', $data->email) }}>
    </div>
    @error('email')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="password">Password: </label>
        <input class="password_input" type="password" name="password" id="password" placeholder="Enter input....">
        <input type="checkbox" name="password_checkbox" class="password_checkbox"> Show Password
    </div>
    @error('password')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    <div class="mt-3">
        <label class="w-100" for="confirm_password">Konfirmasi Password: </label>
        <input class="password_input" type="password" name="confirm_password" id="confirm_password" placeholder="Enter input....">
        <input type="checkbox" name="password_checkbox" class="password_checkbox"> Show Password
    </div>
    @error('confirm_password')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
    </div>
    <div class="flex gap-5 mt-3">
        <button class="btn bg-(--blue-dark-mode-color) text-white" type="submit">Submit</button>
        <a href={{ route('siswa.akun.index') }} class="btn bg-(--red-dark-mode-color) text-white">Cancel</a>
    </div>
</form>
@endsection