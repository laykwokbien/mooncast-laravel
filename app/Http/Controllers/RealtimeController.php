<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use App\Models\prediction;
use App\Models\ProximoPeriod;
use App\Models\realtime;
use App\Models\siswa;
use Arr;
use Carbon\Carbon;
use Http;
use Illuminate\Http\Request;

class RealtimeController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        
        $selected = $request->input('selected');

        $siswa = null;
        $data = null;

        if ($search != null || $search != ''){
            $siswa = siswa::where('nama_siswa', 'like', "%{$search}%")->with('isJurusan', 'allRealtimeData')->paginate(10);
        }
        
        if ($selected != null || $selected != ''){
            $data = ProximoPeriod::whereHas('isSiswa', function ($q) use($selected){
                $q->where('nama_siswa', 'like', "%{$selected}%");
            })->with('isSiswa')->paginate(10);
        }

        return view('prediction.realtime.index', compact('data', 'siswa'));
    }

     function predict_page($id){
        $data = siswa::find($id);
        return view('prediction.realtime.predict', compact('data'));
    }

    function predict($id, Request $request){
        $validated = $request->validate([
            'nis' => 'required',
            'umur' => 'required|integer|min:15|max:19',
            'semester' => 'required|min:1|max:6',
            'tanggal_mulai' => 'required|date',
            'suhu_tubuh' => 'required|decimal:0,1,2|min:35|max:40',
            'masalah_kesehatan' => 'required|in:Ya,Tidak',
            'rokok' => 'required|in:Ya,Tidak',
            'aktivitas_fisik' => 'required|in:Tidak,Ringan,Sedang,Berat',
            'stress' => 'required|in:Tidak,Rendah,Sedang,Tinggi',
            'obat' => 'required|in:Ya,Tidak',
        ], [
            'nis' => 'NIS Wajib untuk diisi',
            'umur.required' => 'Umur Wajib untuk diisi',
            'umur.integer' => 'Umur harus dalam bentuk angka',
            'umur.min' => 'Umur Minimal 14 Tahun',
            'umur.max' => 'Umur Minimal 19 Tahun',
            'semester.required' => 'Semester Wajib untuk diisi',
            'semester.integer' => 'Semester harus dalam bentuk angka',
            'semester.min' => 'Minimal Semester 1',
            'semester.max' => 'Maksimal Semester 6',
            'tanggal_mulai.required' => 'Tanggal Mulai Wajib untuk diisi',
            'tanggal_mulai.date' => 'Tanggal Mulai harus dalam bentuk Tanggal/Date',
            'suhu_tubuh.required' => 'Suhu Tubuh Wajib untuk diisi',
            'suhu_tubuh.decimal' => 'Suhu Tubuh harus dalam bentuk angka desimal',
            'suhu_tubuh.min' => 'Suhu Tubuh minimal 35 Derajat',
            'suhu_tubuh.max' => 'Suhu Tubuh minimal 40 Derajat',
            'masalah_kesehatan.required' => 'Baris Masalah Kesehatan Wajib untuk diisi',
            'masalah_kesehatan.in' => 'Baris Masalah Kesehatan Hanya bisa diisi dengan Ya dan Tidak',
            'rokok.required' => 'Baris Rokok Wajib untuk diisi',
            'rokok.in' => 'Baris Rokok Hanya bisa diisi dengan Ya dan Tidak',
            'aktivitas_fisik.required' => 'Baris Aktivitas Fisik Wajib untuk diisi',
            'aktivitas_fisik.in' => 'Baris Aktivitas Fisik Hanya bisa diisi dengan Tidak, Ringan, Sedang dan Berat',
            'stress.required' => 'Baris Stress Wajib untuk diisi',
            'stress.in' => 'Baris Stress Hanya bisa diisi dengan Tidak, Rendah, Sedang dan Tinggi',
            'obat.required' => 'Baris Obat Wajib untuk diisi',
            'obat.in' => 'Baris Obat Hanya bisa diisi dengan Ya dan Tidak',
        ]);
        
        $input = $validated;

        $input['bulan'] = date('m', strtotime($input['tanggal_mulai']));
        $input['tahun'] = date('Y', strtotime($input['tanggal_mulai']));
        

        $response = Http::post('http://localhost:5000/predict', Arr::except($input, ['nis', 'jurusan', 'tanggal_mulai', 'nama']))->json();

        $validated['durasi_siklus'] = $response['durasi_siklus'][0];
        $validated['durasi_haid'] = $response['durasi_haid'][0];
        
        $date = Carbon::parse($validated['tanggal_mulai']);        
        $data = [];
        
        for ($i = 1; $i < count($response['durasi_siklus']); $i++){
            $date->addDays($response['durasi_siklus'][$i]);
            $data[] = ['tanggal_mulai_haid' => $date->format('Y-m-d'), 'durasi_haid' => $response['durasi_haid'][$i]];
        }
        
        for ($i = 1; $i < 13; $i++){   
            prediction::updateOrCreate(['nis'=> $validated['nis'], 'month_index' => $i], $data[$i - 1]);
        }
        ProximoPeriod::updateOrCreate(['nis' => $validated], Arr::except($validated, ['tanggal_mulai', 'durasi_haid', 'durasi_siklus']));
        realtime::create($validated);
        
        return redirect()->route('realtime.index', ['selected' => $request->input('nama_siswa')])->with('success', 'Prediksi Berhasil untuk Dibuat');
    }

    function data_index(Request $request){
        $search = $request->input('search');

        $data = realtime::join('siswas', 'siswas.nis', '=', 'realtimes.nis')
        ->when($search, function ($query) use ($search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%");
            })->orWhere('siswas.nis', 'like', "%{$search}%");
        })
        ->with('siswa')
        ->orderBy('siswas.nama_siswa', 'asc')
        ->select('realtimes.*')
        ->paginate(10);

        return view('realtime.index', compact('data'));
    }

    public function show($id){
        $data = realtime::with('siswa')->find($id);
        return back()->with('detailData', $data->toArray());
    }

    function insert(Request $request){
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.' => 'exists:realtimes,id'
        ], [
            'user_ids.required' => 'Mohon Pilih Salah Satu Checkbox milih user',
            'user_ids.array' => 'Data yang telah dipilih harus dalam bentuk Array',
            'user_ids.exists' => 'User harus terdata',
        ]);

        $ids = $request->input('user_ids');
        $datas = realtime::whereIn('id',$ids)->get();
        foreach ($datas as $data){
            bulan::create($data->toArray());
        }
        realtime::whereIn('id', $ids)->delete();
        return redirect()->route('realtime.data')->with('success', 'Data Berhasil untuk Ditambahkan');
    }

    function update_page($id){
        $data = realtime::with('siswa')->find($id);
        return view('realtime.update', compact('data'));
    }

    function update($id, Request $request){
         $validated = $request->validate([
            'nis' => 'required',
            'umur' => 'required|integer|min:15|max:19',
            'semester' => 'required|min:1|max:6',
            'tanggal_mulai' => 'required|date',
            'suhu_tubuh' => 'required|decimal:0,1,2|min:35|max:40',
            'masalah_kesehatan' => 'required|in:Ya,Tidak',
            'rokok' => 'required|in:Ya,Tidak',
            'aktivitas_fisik' => 'required|in:Tidak,Ringan,Sedang,Berat',
            'stress' => 'required|in:Tidak,Rendah,Sedang,Tinggi',
            'obat' => 'required|in:Ya,Tidak',
        ], [
            'nis' => 'NIS Wajib untuk diisi',
            'umur.required' => 'Umur Wajib untuk diisi',
            'umur.integer' => 'Umur harus dalam bentuk angka',
            'umur.min' => 'Umur Minimal 14 Tahun',
            'umur.max' => 'Umur Minimal 19 Tahun',
            'semester.required' => 'Semester Wajib untuk diisi',
            'semester.integer' => 'Semester harus dalam bentuk angka',
            'semester.min' => 'Minimal Semester 1',
            'semester.max' => 'Maksimal Semester 6',
            'tanggal_mulai.required' => 'Tanggal Mulai Wajib untuk diisi',
            'tanggal_mulai.date' => 'Tanggal Mulai harus dalam bentuk Tanggal/Date',
            'suhu_tubuh.required' => 'Suhu Tubuh Wajib untuk diisi',
            'suhu_tubuh.decimal' => 'Suhu Tubuh harus dalam bentuk angka desimal',
            'suhu_tubuh.min' => 'Suhu Tubuh minimal 35 Derajat',
            'suhu_tubuh.max' => 'Suhu Tubuh minimal 40 Derajat',
            'masalah_kesehatan.required' => 'Baris Masalah Kesehatan Wajib untuk diisi',
            'masalah_kesehatan.in' => 'Baris Masalah Kesehatan Hanya bisa diisi dengan Ya dan Tidak',
            'rokok.required' => 'Baris Rokok Wajib untuk diisi',
            'rokok.in' => 'Baris Rokok Hanya bisa diisi dengan Ya dan Tidak',
            'aktivitas_fisik.required' => 'Baris Aktivitas Fisik Wajib untuk diisi',
            'aktivitas_fisik.in' => 'Baris Aktivitas Fisik Hanya bisa diisi dengan Tidak, Ringan, Sedang dan Berat',
            'stress.required' => 'Baris Stress Wajib untuk diisi',
            'stress.in' => 'Baris Stress Hanya bisa diisi dengan Tidak, Rendah, Sedang dan Tinggi',
            'obat.required' => 'Baris Obat Wajib untuk diisi',
            'obat.in' => 'Baris Obat Hanya bisa diisi dengan Ya dan Tidak',
        ]);

        realtime::where('id', $id)->update($validated);
        
        return redirect()->route('realtime.data')->with('success', 'Data berhasil untuk diperbarui');
    }
    
    public function delete($id){
        realtime::where('id', $id)->delete();
        
        return redirect()->route('realtime.data')->with('success', 'Data Berhasil untuk dihapus');
    }
}
