<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulan;

class bulansController extends Controller
{
    function index(){
        $data = Bulan::paginate(10);
        return view('bulans.index', compact('data'));
    }

    function create_page(){
        return view('bulans.create');
    }

    function create(Request $request) {
        $validated = $request->validate([
            'umur' => 'required|integer|min:14|max:19',
            'semester' => 'required|integer|min:1|max:6',
            'tanggal_mulai' => 'required|date',
            'durasi_siklus' => 'required|integer|min:1|max:35',
            'durasi_haid' => 'required|integer|min:1|max:15',
            'suhu_tubuh' => 'required|decimal:0,1,2|min:35|max:40',
            'masalah_kesehatan' => 'required|in:Ya,Tidak',
            'rokok' => 'required|in:Ya,Tidak',
            'aktivitas_fisik' => 'required|in:Tidak,Ringan,Sedang,Berat',
            'stress' => 'required|in:Tidak,Rendah,Sedang,Tinggi',
            'obat' => 'required|in:Ya,Tidak',
        ], [
            'umur.required' => 'Umur wajib untuk diisi',
            'umur.integer' => 'Umur harus dalam bentuk angka',
            'umur.min' => 'Umur Minimal 14 Tahun',
            'umur.max' => 'Umur Minimal 19 Tahun',
            'semester.required' => 'Semester Wajib untuk diisi',
            'semester.integer' => 'Semester harus dalam bentuk angka',
            'semester.min' => 'Minimal Semester 1',
            'semester.max' => 'Maksimal Semester 6',
            'tanggal_mulai.required' => 'Tanggal Mulai Wajib untuk diisi',
            'tanggal_mulai.date' => 'Tanggal Mulai harus dalam bentuk Tanggal/Date',
            'durasi_siklus.required' => 'Durasi Siklus Wajib untuk diisi',
            'durasi_siklus.integer' => 'Durasi Siklus harus dalam bentuk angka',
            'durasi_siklus.min' => 'Durasi Siklus Minimal 1 Hari',
            'durasi_siklus.max' => 'Durasi Siklus Maksimal 35 Hari',
            'durasi_haid.required' => 'Durasi Haid Wajib untuk diisi',
            'durasi_haid.integer' => 'Durasi Haid harus dalam bentuk angka',
            'durasi_haid.min' => 'Durasi Haid Minimal 1 Hari',
            'durasi_haid.max' => 'Durasi Haid Maksimal 15 Hari',
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
        
        Bulan::create($validated);
        
        return redirect('/bulans')->with('success', 'Data baru berhasil untuk dibuat');
    }

    function update_page($id){
        $data = Bulan::find($id);
        return view('bulans.update', compact('data'));
    }

    function update($id, Request $request) {
        $validated = $request->validate([
            'umur' => 'required|integer|min:14|max:19',
            'semester' => 'required|integer|min:1|max:6',
            'tanggal_mulai' => 'required|date',
            'durasi_siklus' => 'required|integer|min:1|max:35',
            'durasi_haid' => 'required|integer|min:1|max:15',
            'suhu_tubuh' => 'required|decimal:0,1,2|min:35|max:40',
            'masalah_kesehatan' => 'required|in:Ya,Tidak',
            'rokok' => 'required|in:Ya,Tidak',
            'aktivitas_fisik' => 'required|in:Tidak,Ringan,Sedang,Berat',
            'stress' => 'required|in:Tidak,Rendah,Sedang,Tinggi',
            'obat' => 'required|in:Ya,Tidak',
        ], [
            'umur.required' => 'Umur wajib untuk diisi',
            'umur.integer' => 'Umur harus dalam bentuk angka',
            'umur.min' => 'Umur Minimal 14 Tahun',
            'umur.max' => 'Umur Minimal 19 Tahun',
            'semester.required' => 'Semester Wajib untuk diisi',
            'semester.integer' => 'Semester harus dalam bentuk angka',
            'semester.min' => 'Minimal Semester 1',
            'semester.max' => 'Maksimal Semester 6',
            'tanggal_mulai.required' => 'Tanggal Mulai Wajib untuk diisi',
            'tanggal_mulai.date' => 'Tanggal Mulai harus dalam bentuk Tanggal/Date',
            'durasi_siklus.required' => 'Durasi Siklus Wajib untuk diisi',
            'durasi_siklus.integer' => 'Durasi Siklus harus dalam bentuk angka',
            'durasi_siklus.min' => 'Durasi Siklus Minimal 1 Hari',
            'durasi_siklus.max' => 'Durasi Siklus Maksimal 35 Hari',
            'durasi_haid.required' => 'Durasi Haid Wajib untuk diisi',
            'durasi_haid.integer' => 'Durasi Haid harus dalam bentuk angka',
            'durasi_haid.min' => 'Durasi Haid Minimal 1 Hari',
            'durasi_haid.max' => 'Durasi Haid Maksimal 15 Hari',
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
        
        Bulan::where('id', $id)->update($validated);
        
        return redirect('/bulans')->with('success', 'Data berhasil untuk diupdate');
    }

    function delete($id){
        Bulan::where('id', $id)->delete();
        return back()->with('success', 'Data berhasil untuk dihapus');
    }
}
