@extends('template.master')

@section('dashboard')

@php
    $status = 'aktif';
    if (isset($_GET['status'])){
        $status = $_GET['status'];
    }
@endphp

<div class="flex flex-col gap-4 mt-3">
    <h1>Control Panel Operator</h1>
    <p>Jumlah Operator: {{ $data->total() }}</p>
    <form class="flex gap-10 mb-10" method="get">
        <label class="w-50" for="status">Status Akun:</label>
        <select name="status" id="status">
            <option @if ($status == 'aktif') {{ "selected" }} @endif value="aktif">Aktif</option>
            <option @if ($status == 'tidak aktif') {{ "selected" }} @endif value="tidak aktif">Tidak Aktif</option>
        </select>
        <input style="width:25%;" type="text" name="search" id="search" placeholder="Cari....">
        <button type="submit" class="btn bg-(--blue-dark-mode-color)">Cari</button>
    </form>
</div>

<form action="{{ route('operator.reset') }}" method="post" class="flex flex-col gap-5">
    @csrf
    <div class="dropdown-button" id='dropdown-button'>
        <div class="w-50 bg-(--blue-dark-mode-color) text-white p-2 rounded-md relative cursor-pointer" id="dropdown-handler">Aksi<i class="bi bi-chevron-down absolute right-3"></i></div>
        <div class="close-dropdown-body" id="dropdown-body">
            <button type="submit">Reset Password</button>
            <a href="{{ route('operator.create') }}">Tambah Akun</a>
        </div>
    </div>
    <table class="table table-auto md:table-fixed w-100em border-collapse rounded-md">
        <thead>
            <tr>
                <th class="text-start border-y border-indigo-500 px-3 py-1"></th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">No.</th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">Nama Operator</th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">Email</th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">Default Password</th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">Status</th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">Dibuat pada</th>
                <th class="text-start border-y border-indigo-500 px-3 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (count($data) > 0)
            @foreach ($data as $key => $row)
            <tr>
                @php
                    $check = False;
                    if ($row['status'] == 'aktif'){
                        $check = True;
                    }
                @endphp
                <td class="text-start border-y border-indigo-500 px-3 py-2"><input type="checkbox" name="user_ids[]" id="user_ids[]" value="{{ $row->id }}"></td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->nama }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->email }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">@if (Hash::check($row->default_password, $row->password)) {{ $row->default_password }} @else {{ 'Password Sudah Diubah' }} @endif</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2"><div style='width: fit-content;' class="btn @if ($check) {{'bg-green-400'}} @else {{ 'bg-red-400' }} @endif text-white" style="width:80px; font-weight: 500; cursor:default;">@if ($check) {{ 'Aktif' }} @else {{ 'Tidak Aktif' }} @endif</div></td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->created_at }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">
                    <div class="flex gap-5">
                        <a href={{ route('operator.update', ['id' => $row->id]) }} class="btn bg-(--yellow-dark-mode-color) font-bold text-white">Ubah</a>
                        <a href={{ route('operator.destroy', ['id'=>$row->id]) }} class="btn bg-(--red-dark-mode-color) font-bold text-white">Hapus</a>
                    </div>
                </td>
            </tr>
            @endforeach
            @else
            <p class="font-medium">No Record</p>
            @endif
        </tbody>
    </table>
    <div class="table-page">
        {{ $data->links() }}
        <p class="ml-5">Page {{$data->currentPage()}}/{{ $data->lastPage() }}</p>
        <p class='ml-5 font-medium'>go to Page</p>
        <input class="mx-2" style="width: 50px" type="text" name="search-page" id="search-page">
        <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('operator.index') }}>Search</buttton>
    </div>
</form>
@endsection