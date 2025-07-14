<?php

namespace App\Http\Controllers;

use App\Imports\SiswaDataImport;
use App\Models\jurusan;
use App\Models\siswa;
use Arr;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $selectedJurusan = $request->input('jurusan');
        $jurusan = [
            'data' => jurusan::get(),
            'selected' => $selectedJurusan
        ];
        $today = now();
        $data = siswa::when($search||$selectedJurusan, function($query) use ($search, $selectedJurusan) {
            $query
            ->when($selectedJurusan, function ($q, $selectedJurusan){
                $q->WhereHas('isJurusan', function ($subquery) use ($selectedJurusan) {
                    $subquery->where('nama_jurusan', 'like', "%{$selectedJurusan}%");
                });
            })
            ->when($search, function ($q, $search){
                $q->WhereHas('hasAccount', function ($subquery) use ($search){
                    $subquery->where('nama', 'like', "%{$search}%");
                });
            });
        })->whereNot('nis', 12)->with(['isJurusan', 'isPred', 'hasAccount'])->paginate(5);

        return view('siswa.data.index', compact('data', 'jurusan'));
    }

     public function create_page(){
        $jurusan = jurusan::get();
        return view('siswa.data.create', compact('jurusan'));
    }

    public function create(Request $request){
        $validated = $request->validate([
            'nama_siswa' => 'required|string|unique:siswas,nama_siswa',
            'nis' => 'required|integer|digits:10|unique:siswas,nis',
            'alamat' => 'nullable',
            'jurusan' => 'required|exists:jurusans,id',
            'tahun_masuk' => 'required|digits:4',
            'tanggal_lahir' => 'nullable|date',
            'tahun_lulus' => 'nullable|digits:4',
        ], [
            'nama_siswa.required' => 'Baris Nama Siswa Wajib untuk diisi',
            'nama_siswa.string' => 'Baris Nama Siswa harus menggunakan huruf',
            'nama_siswa.unique' => 'Nama yang digunakan pada Baris Nama Siswa sudah digunakan',
            'nis.required' => 'NIS Wajib untuk diisi',
            'nis.integer' => 'NIS harus dalam bentuk angka',
            'nis.digits' => 'NIS harus memiliki 10 angka',
            'nis.unique' => 'NIS yang digunakan sudah terdaftar',
            'jurusan.required' => 'Baris Jurusan Wajib untuk diisi',
            'jurusan.exists' => 'Jurusan yang dimasukkan harus terdaftar',
            'tahun_masuk.required' => 'Baris Tahun Masuk Harus diisi',
            'tahun_masuk.digits' => 'Baris Tahun Masuk harus memiliki 4 angka',
            'tanggal_lahir.date' => 'Tanggal Lahir yang diisi harus dalam bentuk Date',
            'tahun_lulus.digits' => 'Baris Tahun Lulus harus memiliki 4 angka',
        ]);

        if ($validated['tahun_lulus'] == '' || $validated['tahun_lulus'] == null){
            $validated['tahun_lulus'] = 0;
        }

        siswa::create($validated);
        
        return redirect()->route('siswa.data.index')->with('success', 'Data Baru Berhasil untuk dibuat');
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new SiswaDataImport;
        
        Excel::import($import, $request->file('file'));
        
        if ($import->failures()->isNotEmpty()){           
            return back()->with(
                'fails', $import->failures() 
            );
        }

        return back()->with('success', 'Data berhasil untuk diimport ');
    }

    function update_page($id){
        $data = siswa::with('hasAccount')->find($id);
        $jurusan = jurusan::get();
        return view('siswa.data.update', compact('data', 'jurusan'));
    }

    function update($id, Request $request){
        $validated = $request->validate([
            'nis' => 'required|digits:10',
            'nama_siswa' => 'required|unique:siswas,nama_siswa,'.$id,
            'alamat' => 'nullable',
            'jurusan' => 'required|exists:jurusans,id',
            'tahun_masuk' => 'required|digits:4',
            'tanggal_lahir' => 'nullable|date',
            'tahun_lulus' => ['nullable', function ($attributes, $value, $fail){
                if ((int)$value == 0) {
                    return;
                } else if (is_numeric($value) && strlen($value) != 4)
                {
                    $fail('Tahun Lulus harus 4 digits');
                }
            }, 'regex:/^[0-9]+$/'],
        ], [
            'nama_siswa.required' => 'Baris Nama Siswa Wajib untuk diisi',
            'nama_siswa.string' => 'Baris Nama Siswa harus menggunakan huruf',
            'nama_siswa.unique' => 'Nama yang digunakan pada Baris Nama Siswa sudah digunakan',
            'nis.required' => 'NIS Wajib untuk diisi',
            'nis.integer' => 'NIS harus dalam bentuk angka',
            'nis.digits' => 'NIS harus memiliki 10 angka',
            'nis.unique' => 'NIS yang digunakan sudah terdaftar',
            'alamat.required' => 'Baris Alamat Wajib untuk diisi',
            'jurusan.required' => 'Baris Jurusan Wajib untuk diisi',
            'jurusan.exists' => 'Jurusan yang dimasukkan harus terdaftar',
            'tahun_masuk.required' => 'Baris Tahun Masuk Harus diisi',
            'tahun_masuk.digits' => 'Baris Tahun Masuk harus memiliki 4 angka',
            'tanggal_lahir.date' => 'Tanggal Lahir yang diisi harus dalam bentuk Date',
            'tahun_lulus.regex' => 'Baris Tahun Lulus hanya bisa diisi dengan Angka 1 sampai dengan 9',
        ]);

        $exclude = [];
        if ($validated['tahun_lulus'] == null || $validated['tahun_lulus'] == ''){
            $exclude[] = 'tahun_lulus';
        }

        siswa::where('id', $id)->update(Arr::except($validated, $exclude));
        
        return redirect()->route('siswa.data.index')->with('success', 'Data berhasil untuk diperbarui');
    }

    function delete($id){
        siswa::where('id', $id)->delete();
        return back()->with('success', 'Data berhasil untuk dihapus');
    }
}
