@extends('template.master')

@section('dashboard')

<div class="flex flex-col gap-2 mt-3">
    <h1>Control Panel Jurusan</h1>
    <a href={{ route('jurusan.create') }} class="btn text-white bg-indigo-700">Create</a>
</div>



<table class="table table-auto md:table-fixed w-100em border-collapse rounded-md">
    <thead>
        <tr>
            <th class="text-start border-y border-indigo-500 px-3 py-1">No.</th>
            <th class="text-start border-y border-indigo-500 px-3 py-1">Nama Jurusan</th>
            <th class="text-start border-y border-indigo-500 px-3 py-1">Jumlah Siswa</th>
            <th class="text-start border-y border-indigo-500 px-3 py-1">Dibuat pada</th>
            <th class="text-start border-y border-indigo-500 px-3 py-1">Diperbarui pada</th>
            <th class="text-start border-y border-indigo-500 px-3 py-1">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data) > 0)
        @foreach ($data as $key => $row)
        <tr>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $data->firstItem() + $key }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->nama_jurusan }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->Siswas->count() }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->created_at }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">{{ $row->updated_at }}</td>
            <td class="text-start border-y border-indigo-500 px-3 py-2">
                <div class="flex gap-5">
                    <a href={{ route('jurusan.update', ['id'=>$row->id]) }} class="btn bg-(--yellow-dark-mode-color) font-bold text-white">Ubah</a>
                    <a href={{ route('jurusan.destroy', ['id'=>$row->id]) }} class="btn bg-(--red-dark-mode-color) font-bold text-white">Hapus</a>
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
    <button class="mx-5 btn bg-violet-600 text-white" id="search-btn" data-url={{ route('jurusan.index') }}>Search</buttton>
</div>
@endsection