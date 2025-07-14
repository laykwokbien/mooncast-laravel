@extends('template.master')

@section('dashboard')
@if (session()->has('fails'))
    {{ 'error' }}
@endif

<form class='flex flex-col w-100' action={{ route('jurusan.store') }} method="POST">
    @csrf
    <div class="flex flex-row gap-5 w-dvh">
        <div class="mb-3">
            <label for="nama_jurusan">Nama Jurusan: </label>
            <input type="text" name="nama_jurusan" id="nama_jurusan" placeholder="Enter input....">
            @error('nama_jurusan')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="flex gap-5 mt-3">
        <button class="btn bg-(--blue-dark-mode-color) text-white" type="submit">Submit</button>
        <a href={{ route('jurusan.index') }} class="btn bg-(--red-dark-mode-color) text-white">Cancel</a>
    </div>
</form>

@endsection