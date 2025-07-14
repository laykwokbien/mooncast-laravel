<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>

<body class="bg-(--main-color) text-white flex flex-row relative">
    @if (session()->has('detailPred'))
    <div id="detail" class="w-screen h-screen absolute z-100">
        <div class="detail-info">
            <div id="close-detail"><i class="bi bi-x-lg"></i></div>
            <p>Nama: {{ session('detailPred')['nama_siswa'] }}</p>
            <p>Umur: {{ session('detailPred')['is_proximo']['umur'] }}</p>
            <p>Jurusan: {{ session('detailPred')['is_jurusan']['nama_jurusan'] }}</p>
            <p>Semester: {{ session('detailPred')['isProximo']['semester'] }}</p>
            <hr>
            <p>Data Terakhir Diubah : {{ session('detailPred')['isProximo']['updated_at'] }}</p>
            <p>Rekor Kesehatan <i class="bi bi-bandaid-fill"></i></p>
            <ul class="ml-5 list-disc">
                <li>Suhu Tubuh: {{ session('detailPred')['isProximo']['suhu_tubuh'] }}</li>
                <li>Masalah Kesehatan: {{ session('detailPred')['isProximo']['masalah_kesehatan'] }}</li>
                <li>Rokok: {{ session('detailPred')['isProximo']['rokok'] }}</li>
                <li>Stress: {{ session('detailPred')['isProximo']['stress'] }}</li>
                <li>Konsumsi Obat Tertentu: {{ session('detailPred')['isProximo']['obat'] }}</li>
            </ul>
            @php
                dd(session('detailPred'));
                $period = session('detailPred')['is_period']
            @endphp
            <p>Durasi Haid Bulan ini: <br> @if ($period['haid']) {{ "{$period['start']} - {$period['end']}" }} @else {{ 'Tidak Haid' }} @endif</p>
            <p>Durasi Haid Bulan depan: <br> {{ "{$period['next']} - {$period['nextend']}" }}</p>
        </div>
    </div>
    @endif

    @if (session()->has('detailData'))
    <div id="detail" class="w-screen h-screen absolute z-100">
        <div class="detail-info">
            <div id="close-detail"><i class="bi bi-x-lg"></i></div>
            <p>Nama: {{ session('detailData')['siswa']['nama_siswa'] }}</p>
            <p>Umur: {{ session('detailData')['umur'] }}</p>
            <p>Semester: {{ session('detailData')['semester'] }}</p>
            <hr>
            @php
                $updated_at = strtotime(session('detailData')['updated_at']);
                $created_at = strtotime(session('detailData')['created_at']);
                $tanggal_mulai = strtotime(session('detailData')['tanggal_mulai']);
            @endphp
            <p>Data Dibuat : {{ date('d M Y H:i:s', $created_at) }}</p>
            <p>Terakhir Diubah : {{ date('d M Y H:i:s', $updated_at) }}</p>
            <p>Rekor Kesehatan <i class="bi bi-bandaid-fill"></i></p>
            <ul class="ml-5 list-disc">
                <li>Tanggal Terakhir Haid: {{ date('d M Y', $tanggal_mulai) }}</li>
                <li>Suhu Tubuh: {{ session('detailData')['suhu_tubuh'] }}</li>
                <li>Masalah Kesehatan: {{ session('detailData')['masalah_kesehatan'] }}</li>
                <li>Rokok: {{ session('detailData')['rokok'] }}</li>
                <li>Stress: {{ session('detailData')['stress'] }}</li>
                <li>Konsumsi Obat Tertentu: {{ session('detailData')['obat'] }}</li>
            </ul>
            <hr>
            <div class="flex gap-5">
                <a href={{ route('realtime.update', ['id'=> session('detailData')['id']]) }} class="btn bg-(--yellow-dark-mode-color) font-bold text-white">Ubah</a>        
                <form action="{{ route('realtime.destroy', ['id' => session('detailData')['id'] ]) }}" method="post">
                    @csrf
                    <button type='submit' class="btn bg-(--red-dark-mode-color) font-bold text-white">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('detailSiswa'))
    <div id="detail" class="w-screen h-screen absolute z-100">
        <div class="detail-info">
            <div id="close-detail"><i class="bi bi-x-lg"></i></div>
            <p>Nama Siswa: {{ session('detailSiswa')['nama_siswa'] }}</p>
            <hr>
            <p>Informasi Siswa:</p>
            <p>NIS: {{ session('detailSiswa')['nis'] }}</p>
            <p>Alamat: {{ session('detailSiswa')['alamat'] }}</p>
            <p>Tanggal Lahir: {{ date('d M Y',strtotime(session('detailSiswa')['tanggal_lahir'])) }}</p>
            <p>Tahun Masuk: {{ session('detailSiswa')['tahun_masuk'] }}</p>
            <p>Jurusan: {{ session('detailSiswa')['is_jurusan']['nama_jurusan'] }}</p>
            <p>Terakhir Data Diubah: {{ date('d M Y H:i:s',strtotime(session('detailSiswa')['updated_at'])) }}</p>
        </div>
    </div>
    @endif

    @if (session()->has('success'))
        <div id="top-popup" class="flex flex-row justify-center text-white w-screen fixed bg-green-500 z-100 transition-all" style="height: 30px">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('fail'))
        <div id="top-popup" class="flex flex-row justify-center text-white w-screen fixed bg-red-500 z-100 transition-all" style="height: 30px">
            {{ session('fail') }}
        </div>
    @endif

    @error('siswa_ids')
    <div id="top-popup" class="flex flex-row justify-center text-white w-screen fixed bg-red-500 z-100 transition-all" style="height: 30px">
        {{ $message }}
    </div>
    @enderror

    @error('user_ids')
    <div id="top-popup" class="flex flex-row justify-center text-white w-screen fixed bg-red-500 z-100 transition-all" style="height: 30px">
        {{ $message }}
    </div>
    @enderror

    @if (session()->has('fails'))
        <div id="top-popup" class="flex flex-row justify-center text-white w-screen fixed bg-red-500 z-100 transition-all" style="height: 30px">
            @foreach (session('fails') as $fail)
            <p>Baris {{ $fail->row() }} - Kolom {{ $fail->attribute() }}: {{ implode(', ', $fail->errors()) }}</p>
            @endforeach
        </div>
    @endif

    @error('file')
    <div id="top-popup" class="flex flex-row justify-center text-white w-screen fixed bg-red-500 z-100 transition-all" style="height: 30px">
        {{ $message }}
    </div>
    @enderror
    <header class="gap-5 expand py-20">
        <div id="collapse_expandbtn" class="absolute top-5 text-white"><i id="expand_collapseIcon" class="bi bi-chevron-double-left"></i><span class='nav-text'> Collapse</span></div>
        <a class="text-white" href={{ route('settings') }}><i class="bi bi-person"></i><span class='nav-text'> {{ Auth::user()->nama }}</span></a>
        <hr>
        <a class="text-white" href="/dashboard"><i class="bi bi-house"></i><span class='nav-text'> Dashboard</span></a>
        <div class="dropdown">
            <div class="dropdown-btn" id="dropdown-btn">
                <i class="bi bi-people-fill"></i><span class="nav-text">Akun<i class="bi bi-chevron-right"></i></span>
            </div>
            <ul class="submenu close-dropdown" id="dropdown-menu">
                <li>
                    <a class="text-white" href={{ route('siswa.akun.index') }}><span class='dropdown-text'> Akun Siswa</span></a>
                </li>
                <li>
                    <a class="text-white" href={{ route('siswa.akun.generate') }}><span class='dropdown-text'> Generate Akun Siswa</span></a>
                </li>
                @if (Auth::user()->role == 'admin')
                <li>
                    <a class="text-white" href={{ route('operator.index') }}><span class='dropdown-text'> Operator</span></a>
                </li>
                @endif
            </ul>
        </div>
        <div class="dropdown">
            <div class="dropdown-btn" id="dropdown-btn">
                <i class="bi bi-database-fill"></i><span class="nav-text">Data<i class="bi bi-chevron-right"></i></span>
            </div>
            <ul class="submenu close-dropdown" id="dropdown-menu">
                @if (Auth::user()->role == 'admin')
                <li>
                    <a class="text-white" href={{ route('jurusan.index') }}><span class='dropdown-text'> Jurusan</span></a>
                </li>
                <li>
                    <a class="text-white" href={{ route('bulan.index') }}><span class='dropdown-text'> Data Traning</span></a>
                </li>
                @endif
                <li>
                    <a class="text-white" href={{ route('siswa.data.index') }}><span class='dropdown-text'> Data Siswa</span></a>
                </li>
                <li>
                    <a class="text-white" href={{ route('realtime.data') }}><span class='dropdown-text'> Data Realtime</span></a>
                </li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="dropdown-btn" id="dropdown-btn">
                <i class="bi bi-graph-up"></i></i><span class="nav-text">Prediksi<i class="bi bi-chevron-right"></i></span>
            </div>
            <ul class="submenu close-dropdown" id="dropdown-menu">
                @if (Auth::user()->role == 'admin')
                <li>
                    <a class="text-white" href={{ route('multi.index') }}><span class='dropdown-text'> Bulan Berikut</span></a>
                </li>
                @endif
                <li>
                    <a class="text-white" href="{{ route('realtime.index') }}"><span class='dropdown-text'> Realtime</span></a>
                </li>
            </ul>
        </div>
        {{-- <a class="text-white" href={{ route('siswa.akun.index') }}><i class="bi bi-people-fill"></i><span class='nav-text'> Akun Siswa</span></a> --}}
        {{-- <a class="text-white" href={{ route('siswa.data.index') }}><i class="bi bi-person-vcard-fill"></i><span class='nav-text'> Data Siswa</span></a> --}}
        {{-- <a class="text-white" href={{ route('jurusan.index') }}><i class="bi bi-person-workspace"></i><span class='nav-text'> Jurusan</span></a> --}}
        {{-- <a class="text-white" href={{ route('bulan.index') }}><i class="bi bi-database-fill"></i><span class='nav-text'> Data Traning</span></a> --}}
        <a class="text-white absolute bottom-15" href={{route('settings')}}><i class="bi bi-gear"></i><span class="nav-text"> Settings</span></a>
        <a class="text-white absolute bottom-5" href={{route('logout')}}><i class="bi bi-box-arrow-left"></i><span class="nav-text"> Log out</span></a>
    </header>