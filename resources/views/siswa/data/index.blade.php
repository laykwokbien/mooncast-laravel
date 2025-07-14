@extends('template.master')

@section('dashboard')
<div class="flex flex-col gap-2 mt-3">
    <h1>Control Panel Data Siswa</h1>
    <p>Jumlah Data: {{ $data->total() }}</p>
    <form class="flex gap-10" method="get">
        <label for="jurusan">Jurusan:</label>
        <select name="jurusan" id="jurusan">
            <option @if ($jurusan['selected'] == null || $jurusan['selected'] == '') {{ 'selected' }} @endif value="">Semua Jurusan</option>
            @foreach ($jurusan['data'] as $row)
                <option @if ($jurusan['selected'] == $row['nama_jurusan']) {{ 'selected' }}  @endif value="{{ $row['nama_jurusan'] }}">{{ $row['nama_jurusan'] }}</option>
            @endforeach
        </select>
        <input style="width:25%;" type="text" name="search" id="search" placeholder="Cari...">
        <button type="submit" class="btn bg-(--blue-dark-mode-color)">Cari</button>
    </form>
</div>

<form action="{{ route('siswa.data.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-5 bg-[#2d1b3b] p-4 rounded-xl shadow-md mb-4 w-fit">
    @csrf
    <div class="flex">
        <input type="file" name="file" id="file" class="hidden" accept=".xlsx,.xls,.csv">
        <label for="file" class="w-fit p-2 bg-(--blue-dark-mode-color) text-white rounded-md cursor-pointer">
            <i class="bi bi-file-earmark-plus-fill"></i> Tambah File
        </label>
        <span class="p-2" id="filename">Belum ada File</span>
    </div>
    <button type="submit" class="btn bg-(--blue-dark-mode-color) text-white">Import</button>
</form>
<a href="{{ route('siswa.data.create') }}" class="w-fit bg-(--blue-dark-mode-color) text-white p-2 rounded-md relative cursor-pointer" id="dropdown-handler">Tambah Data</a>
<table class="table table-auto md:table-fixed w-100em border-collapse rounded-md" style="z-index: 0">
    <thead>
        <tr>
            <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">NIS</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Nama</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Alamat</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Tanggal Lahir</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Jurusan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Tahun Masuk</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Tahun Lulus</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Terakhir Diubah</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data) != 0)            
        @foreach ($data as $key => $row)            
        <tr>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['nis'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['nama_siswa'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['alamat'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['tanggal_lahir'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isJurusan->nama_jurusan }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['tahun_masuk'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">@if ($row['tahun_lulus'] == 0) {{ 'Belum Lulus' }} @else {{ $row['tahun_lulus'] }} @endif</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->updated_at }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">
                <div class="flex gap-5">
                    <a href={{ route('siswa.data.update', ['id' => $row->id]) }} class="btn bg-(--yellow-dark-mode-color) font-bold text-white">Ubah</a>
                    <a href={{ route('siswa.data.destroy', ['id'=>$row->id]) }} class="btn bg-(--red-dark-mode-color) font-bold text-white">Hapus</a>
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
    <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('siswa.data.index') }}>Search</button>
</div>
@endsection