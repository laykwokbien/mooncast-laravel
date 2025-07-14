<?php

namespace App\Http\Controllers;

use App\Models\jurusan;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class JurusanController extends Controller
{
    public function index(){
        $data = jurusan::with('Siswas')->paginate(10);
        return view('jurusan.index', compact('data'));
    }

    public function create_page(){
        return view('jurusan.create');
    }

    public function create(Request $request){
        $validated = $request->validate([
            'nama_jurusan' => 'required|string',
        ],[
            'nama_jurusan.required' => 'Nama jurusan harap diisi',
            'nama_jurusan.string' => 'Nama jurusan hanya dapat diisi dengan Text',
        ]);

        jurusan::create($validated);

        return redirect('/jurusan')->with('success', 'Data baru berhasil untuk ditambahkan');
    }

    public function update_page ($id) {
        $data = jurusan::find($id);
        return view('jurusan.update', compact('data'));
    }

    public function update ($id, Request $request) {
        $validated = $request->validate([
            'nama_jurusan' => 'required|string',
        ],[
            'nama_jurusan.required' => 'Nama jurusan harap diisi',
            'nama_jurusan.string' => 'Nama jurusan hanya dapat diisi dengan Text',
        ]);

        jurusan::where('id', $id)->update($validated);

        return redirect('/jurusan')->with('success', 'Data berhasil untuk diperbarui');
    }

    public function delete($id){
        jurusan::where('id', $id)->delete();
        return redirect('/jurusan')->with('success', 'Data berhasil untuk dihapus');
    }
}
