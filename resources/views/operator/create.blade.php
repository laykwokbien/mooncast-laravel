@extends('template.master')

@section('dashboard')
@if (session()->has('fails'))
    {{ 'error' }}
@endif

<form class='flex flex-col w-100' action={{ route('operator.store') }} method="POST">
    @csrf
    <div class="mt-3">
        <label for="nama">Nama: </label>
        <input type="text" name="nama" id="nama" placeholder="Masukkan Nama..." value={{ old('nama') }}>
    </div>
    @error('nama')
        <div class="text-red-500">{{ $message }}</div>
    @enderror
    
    <div class="mt-3">
        <label for="email">Email: </label>
        <input type="text" name="email" id="email" placeholder="Masukkan Email..." value={{ old('email') }}>
    </div>
    @error('email')
    <div class="text-red-500">{{ $message }}</div>
    @enderror
    <div class="flex gap-5 mt-3">
        <button class="btn bg-(--blue-dark-mode-color) text-white" type="submit">Submit</button>
        <a href={{ route('operator.index') }} class="btn bg-(--red-dark-mode-color) text-white">Cancel</a>
    </div>
</form>
@endsection