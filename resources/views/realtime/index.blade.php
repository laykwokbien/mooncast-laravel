@extends('template.master')

@section('dashboard')


<div class="flex flex-col gap-2 mt-3">
    <h1>Control Panel Data Traning</h1>
    <p>Jumlah Data: {{ $data->total() }}</p>
    <form class="flex gap-10" method="get">
        <input style="width:25%;" type="text" name="search" id="search" placeholder="Cari...">
        <button type="submit" class="btn bg-(--blue-dark-mode-color)">Cari</button>
    </form>
</div>
<form class="flex flex-col gap-10" action="{{ route('realtime.insert') }}" method="post">
    @csrf
    <div class="flex">
        <button class="w-fit font-bold p-2 rounded-md bg-(--blue-dark-mode-color) text-white cursor-pointer" type="submit">
            + Tambahkan Data
        </button>
    </div>
    <table class="table table-auto md:table-fixed w-100em border-collapse rounded-md" style="z-index: 0">
        <thead>  
            <tr>
                <th class="text-start border-y border-indigo-500 px-3 py-2"></th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Nama</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Umur</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Kelas</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Semester</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Terakhir Haid</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Durasi Siklus</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Durasi Haid</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Suhu Tubuh</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Masalah Kesehatan</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Rokok</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Aktivitas Fisik</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Stress</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Obat</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (count($data) > 0)
            @foreach ($data as $key => $row)
            <tr>
                <td class="text-start border-y border-indigo-500 px-3 py-2"><input type="checkbox" name="user_ids[]" id="user_ids[]" value="{{ $row->id }}"></td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->siswa->nama_siswa }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['umur'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->kelas }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ "Semester $row->semester" }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->tanggal_haid }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['durasi_siklus'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['durasi_haid'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['suhu_tubuh'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['masalah_kesehatan'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['rokok'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['aktivitas_fisik'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['stress'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['obat'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">
                    <div class="flex">
                        <a href={{ route('realtime.show', ['id'=>$row->id]) }} class="btn bg-(--blue-dark-mode-color) font-bold text-white">Detail</a>
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
        <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('bulan.index') }}>Search</button>
    </div>
</form>
@endsection