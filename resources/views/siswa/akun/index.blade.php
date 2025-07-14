@extends('template.master')

@section('dashboard')

<div class="flex flex-col gap-2 mt-3">
    <h1>Control Panel Akun Siswa</h1>
    <p>Jumlah Akun Siswa: {{ $data->total() }}</p>
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

<form action="{{ route('siswa.akun.reset') }}" method="post" class="flex flex-col gap-5">
    @csrf
    <button class="w-fit bg-(--red-dark-mode-color) text-white font-bold p-2 rounded-md cursor-pointer" type="submit">Reset Akun</button>
    <table class="table table-auto md:table-fixed w-100em border-collapse rounded-md" style="z-index: 0">
        <thead>
            <tr>
                <th class="text-start border-y border-indigo-500 px-3 py-2"></th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Nama Siswa</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Nama Akun</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Email</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Jurusan</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Password Default</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Dibuat pada</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Diperbarui pada</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (count($data) != 0)            
            @foreach ($data as $key => $row)            
            <tr>
                <td class="text-start border-y border-indigo-500 px-3 py-2"><input type="checkbox" name="user_ids[]" class="child_check" value="{{ $row->id }}"></td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isData->nama_siswa ?? '' }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->nama }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">@if ($row->email != null || $row->email != '') {{ $row->email }} @else {{ "Belum Terdaftar" }} @endif</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->jurusan_name }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">@if (Hash::check($row->default_password, $row->password)) {{ $row->default_password }} @else {{ "Password Sudah Diubah" }} @endif</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ date('d M Y H:i:s', strtotime($row->created_at)) }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ date('d M Y H:i:s', strtotime($row->updated_at)) }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">
                    <div class="flex gap-5">
                        <a href={{ route('siswa.akun.update', ['id' => $row->id]) }} class="btn bg-(--yellow-dark-mode-color) font-bold text-white">Ubah</a>
                        <a href={{ route('siswa.akun.destroy', ['id'=>$row->id]) }} class="btn bg-(--red-dark-mode-color) font-bold text-white">Hapus</a>
                    </div>
                </td>
            </tr>
            @endforeach
            @else
                <p class="font-medium">Tidak ada Data yang tersedia</p>
            @endif
        </tbody>
    </table>
    <div class="table-page">
        {{ $data->links() }}
        <p class="ml-5">Page {{$data->currentPage()}}/{{ $data->lastPage() }}</p>
        <p class='ml-5 font-medium'>go to Page</p>
        <input class="mx-2" style="width: 50px" type="text" name="search-page" id="search-page">
        <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('siswa.akun.index') }}>Search</button>
    </div>
</form>

@endsection