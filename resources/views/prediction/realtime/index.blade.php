@extends('template.master')

@section('dashboard')
<div class="flex flex-col gap-2 mt-3">
    <h1>Control Panel</h1>
    <p>Prediksi Realtime</p>
    @if ($data != null)
    <p>Jumlah Data: {{ $data->Total() }}</p>
    @endif
    <form class="flex gap-10 mb-10" method="get">
        <input style="width:25%;" type="text" name="search" id="search" placeholder="Cari....">
        <button type="submit" class="btn bg-(--blue-dark-mode-color)">Cari</button>
    </form>
</div>
@if ($siswa != null)
<table class="table table-auto md:table-fixed w-100em border-collapse rounded-md overflow-x-auto" style="z-index: 0">
    <thead>
        <tr>
            <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">NIS</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Nama</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Jurusan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Tahun Masuk</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Update Data Terakhir</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $key => $row)
        <tr>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $siswa->firstItem() + $key }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->nis }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->nama_siswa }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isJurusan->nama_jurusan }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->tahun_masuk }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->updated_at }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">
                <div class="flex gap-5">
                    <a href="{{ route('realtime.predict', ['id' => $row->id]) }}" class="btn bg-(--yellow-dark-mode-color) font-bold text-white">Process</a>
                    <form action="{{ route('siswa.show', ['id' => $row->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn bg-(--blue-dark-mode-color) font-bold text-white">Detail</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="table-page">
    {{ $siswa->links() }}
    <p class="ml-5">Page {{$siswa->currentPage()}}/{{ $siswa->lastPage() }}</p>
    <p class='ml-5 font-medium'>go to Page</p>
    <input class="mx-2" style="width: 50px" type="text" name="search-page" id="search-page">
    <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('siswa.data.index') }}>Search</button>
</div>
@endif
@if ($data != null && $siswa == null)            
<table class="table table-auto md:table-fixed w-100em border-collapse rounded-md overflow-x-auto" style="z-index: 0">
    <thead>
        <tr>
            <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Nama</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Umur</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Jurusan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Semester</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Suhu Tubuh</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Aktivitas Fisik</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Stress</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Masalah Kesehatan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Rokok</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Obat Tertentu</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Datang Bulan ini</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Durasi Haid</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Update Data Terakhir</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $row)
        <tr>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isSiswa->nama_siswa }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->umur }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->jurusan }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->semester }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->suhu_tubuh }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->aktivitas_fisik }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->stress }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->masalah_kesehatan }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->rokok }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->obat }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2 font-bold @if ($row->isSiswa->is_period['haid']) {{ 'text-green-500' }} @else {{ 'text-red-500' }} @endif">{{ $row->isSiswa->is_period['message'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2 font-bold @if ($row->isSiswa->is_period['haid']) {{ 'text-green-500' }} @else {{ 'text-red-500' }} @endif">{{ $row->isSiswa->is_period['message'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->updated_at }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">
                <div class="flex gap-5">
                    <form action="{{ route('multi.show', ['id' => $row->isSiswa->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn bg-(--blue-dark-mode-color) font-bold text-white">Detail</button>
                    </form>                
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="table-page">
    {{ $data->links() }}
    <p class="ml-5">Page {{$data->currentPage()}}/{{ $data->lastPage() }}</p>
    <p class='ml-5 font-medium'>go to Page</p>
    <input class="mx-2" style="width: 50px" type="text" name="search-page" id="search-page">
    <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('siswa.data.index') }}>Search</button>
</div>
@endif
@endsection