@extends('template.master')

@section('dashboard')
<div class="flex flex-col gap-2 my-3">
    <h1>Control Panel</h1>
    <p>Prediksi Bulan Berikut</p>
    <p>Jumlah Siswa: @if ($data != null) {{ $data->filter(fn($item) => $item->hasAccount == null)->count() }} @else {{ 0 }} @endif</p>
    <form method="get" class="flex gap-10">
        <select name="jurusan" id="jurusan" style="width: calc(var(--spacing) * 100)">
            <option @if ($jurusan['selected'] == null || $jurusan['selected'] == '') {{ 'selected' }} @endif disabled>--Pilih Jurusan</option>
            @foreach ($jurusan['data'] as $row)
                <option @if ($jurusan['selected'] == $row['nama_jurusan']) {{ 'selected' }}  @endif value="{{ $row['nama_jurusan'] }}">{{ $row['nama_jurusan'] }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn bg-(--blue-dark-mode-color)">Cari</button>
    </form>
</div>
@if ($data != null)         
<form action="{{ route('siswa.akun.store') }}" class="flex flex-col gap-5" method="post">
    @csrf    
    <button type="submit" class="w-fit font-bold p-2 rounded-md bg-(--blue-dark-mode-color) cursor-pointer">Buat Akun</button>
    <div class="flex gap-2 mt-7">
        <input type="checkbox" id="all_checkbox"><label for="all_checkbox">Pilih Semua</label>
    </div>
    <table class="table table-auto md:table-fixed w-100em border-collapse rounded-md" style="z-index: 0">
        <thead>
            <tr>
                <th class="text-start border-y border-indigo-500 px-3 py-2"></th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">No.</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">NIS</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Nama</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Alamat</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Tanggal Lahir</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Jurusan</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Tahun Masuk</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Tahun Lulus</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Punya Akun</th>
                <th class="text-start border-y border-indigo-500 px-3 py-2">Terakhir Diubah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)            
            <tr>
                <td class="text-start border-y border-indigo-500 px-3 py-2"><input type="checkbox" name="siswa_ids[]" class="child_check" value="{{ $row->id }}"></td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['nis'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['nama_siswa'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['alamat'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['tanggal_lahir'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->isJurusan->nama_jurusan }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row['tahun_masuk'] }}</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">@if ($row['tahun_lulus'] == 0) {{ 'Belum Lulus' }} @else {{ $row['tahun_lulus'] }} @endif</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">@if ($row['id_akun'] != null) {{ 'Punya' }} @else {{ 'Tidak' }} @endif</td>
                <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->updated_at }}</td>
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
</form>
@endif
@endsection