@extends('template.master')

@section('dashboard')
<div class="flex gap-10">
    <div class="box-info">
        {{ count($data['siswa']) }}
        <p class="text-info">Jumlah Siswa Terdaftar</p>
    </div>
    @if (Auth::user()->role == 'admin')
    <div class="box-info">
        {{ count($data['operator']) }}
        <p class="text-info">Jumlah Operator yang Terdaftar</p>
    </div>
    <div class="box-info">
        {{ count($data['bulan']) }}
        <div class="text-info">
            <p>Jumlah Data Terdaftar<p>
            <p style="font-size: 12px">Terakhir Update : {{ $data['last_update'] }}<p>
        </div>
    </div>
    @endif

</div>
@endsection