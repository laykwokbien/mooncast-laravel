@extends('template.master')

@section('dashboard')
<div class="flex flex-col gap-2 mt-3">
    <h1>Control Panel</h1>
    <p>Prediksi Bulan Berikut</p>
    <p>Jumlah Siswa: {{ $data->filter(fn($item) => count($item->isPred) != 0)->count() }}</p>
    <form class="flex gap-10 mb-10" method="get">
        <input style="width:25%;" type="text" name="search" id="search" placeholder="Cari....">
        <button type="submit" class="btn bg-(--blue-dark-mode-color)">Cari</button>
    </form>
</div>


<table class="table table-auto md:table-fixed w-100em border-collapse rounded-md overflow-x-auto" style="z-index: 0">
    <thead>
        <tr>
            <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Nama</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Kelas</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Semester</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Umur</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Jurusan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Datang Bulan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Prediksi Durasi Haid Bulan ini</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Prediksi Durasi Haid Bulan Depan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Update Data Terakhir</th>
            <th class="text-start border-y border-indigo-500 px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>

        @if (count($data) != 0)            
        @foreach ($data as $key => $row)
        @if ($row->isProximo != null)
        <tr>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() - 1 + $key }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['nama_siswa'] }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->kelas }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ "Semester {$row->isProximo->semester}" }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isProximo->umur }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isJurusan->nama_jurusan }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">
                <div class="w-30 h-fit text-center @if ($row->is_period['haid']) {{'text-green-500'}} @else {{'text-red-500'}} @endif font-bold px-3 py-2">
                    @if ($row->is_period['haid']) {{ "{$row->is_period['message']}" }} @else {{ $row->is_period['message'] }} @endif
                </div>
            </td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">@if ($row->is_period['haid']) {{ "{$row->is_period['start']} s.d {$row->is_period['end']}" }} @else {{'Tidak Ada'}} @endif</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ "{$row->is_period['next']} s.d {$row->is_period['nextend']}" }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ date('d M Y H:i:s',strtotime($row->updated_at)) }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">
                <div class="flex gap-5">
                    <form action="{{ route('multi.show', ['id' => $row->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn bg-(--blue-dark-mode-color) font-bold text-white">Detail</button>
                    </form>
                </div>
            </td>
        </tr>
        @endif
        @endforeach
        @else
            <p class="font-medium">Tidak ada Data yang tersedia</p>
            <a href="{{ route('realtime.index') }}" class="w-fit p-2 rounded-md bg-(--blue-dark-mode-color) text-white">Tambahkan Prediksi</a>
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