<?php

namespace App\Http\Controllers;

use App\Models\jurusan;
use App\Models\prediction;
use App\Models\siswa;
use App\Models\User;
use Arr;
use DateTime;
use Http;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Http\Request;
use Str;

class SiswaController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $status = $request->input('status');
        if ($status === '' | $status === null){
            $status = 'aktif';
        }
        $data = User::when($search, function($query, $search) {
            $query->where('nama', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        })->where('role', 'siswa')
        ->whereNot('nama', null)
        ->where('status', $status)
        ->with('isData')->paginate(10);
        return view('siswa.akun.index', compact('data', 'status'));
    }
    
    public function show($id){
        $data = siswa::with('isJurusan')->find($id);
        return back()->with('detailSiswa', $data->toArray());
    }
    public function generate_page(Request $request){
        $SelectedJurusan = $request->input('jurusan');
        $jurusan = [
            'selected' => $SelectedJurusan,
            'data' => jurusan::get(),
        ];
        $data = null;
        if ($SelectedJurusan != null){
            $data = siswa::
            when($SelectedJurusan, function ($query) use ($SelectedJurusan){
                $query->whereHas('isJurusan', function ($q) use ($SelectedJurusan) {
                    $q->where('nama_jurusan', 'like', "%{$SelectedJurusan}%");
                });
            })
            ->whereNot('nis', '12')
            ->where('id_akun', null)
            ->with('isJurusan', 'hasAccount')
            ->paginate(10);
        }

        return view('siswa.akun.generate', compact('data', 'jurusan'));
    }

    public function create(Request $request){
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.' => 'exists:siswas,id'
        ], [
            'siswa_ids.required' => 'Tidak ada siswa yang dipilih. Mohon Pilih Salah Satu Checkbox pada tabel',
            'siswa_ids.array' => 'Data yang dipilih harus dalam bentuk Array',
            'siswa_ids.exists' => 'Hanya Bisa Mengisi Data yang sudah terdaftar'
        ]);

        $ids = $request->input('siswa_ids');

        $datas = siswa::whereIn('id', $ids)->get();

        foreach ($datas as $data){
            $default_password = Str::random(8);
            $user = User::create([
                'nama' => $data->nis,
                'password' => bcrypt($default_password),
                'default_password' => $default_password
            ]);

            siswa::where('id', $data->id)->update([
                'id_akun' => $user->id
            ]);
        }
        
        return redirect()->route('siswa.akun.index')->with('success', 'Akun Berhasil untuk dibuat');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.' => 'exists:users,id',
        ], [
            'user_ids.required' => 'Tidak ada pengguna yang dipilih. Mohon Pilih Salah Satu Checkbox pada tabel',
            'user_ids.array' => 'Data yang dipilih harus dalam bentuk Array',
            'user_ids.exists' => 'Hanya Bisa Mengisi Data yang sudah terdaftar'
        ]);

        $ids = $request->input('user_ids');

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user){
            $default = Str::random(8);
            User::where('role', 'siswa')->where('id', $user->id)->update([
                'password' => bcrypt($default),
                'default_password' => $default
            ]);
        }

        return back()->with('success', 'Akun Berhasil untuk Direset');
    }

    public function update_page($id, Request $request){
        $data = User::where('role', 'siswa')->find($id);
        return view('siswa.akun.update', compact('data'));
    }

    public function update($id, Request $request){
        $validated = $request->validate([
            'nama' => 'required|string|unique:users,nama,'.$id,
            'email' => ['nullable','string', function ($attributes, $value, $fail){
                $emails = ['@gmail.com', '@yahoo.com', '@outlook.com'];
                $not_include = true;
                foreach ($emails as $email){
                    if (str_ends_with($value, $email)){
                        $not_include = false;
                    }
                }
                if ($not_include){
                    $fail('Email harus berakhir dengan @gmail.com, @yahoo.com atau @outlook.com');
                }
            },'unique:users,email,'. $id],
            'password' => 'nullable|min:8',
            'confirm_password' => 'nullable|same:password'
        ], [
            'nama.required' => 'Nama Wajib untuk diisi',
            'nama.string' => 'Nama Harus dalam bentuk Huruf',
            'nama.unique' => 'Nama ini sudah digunakan oleh pengguna lain',
            'email.string' => 'Email Harus dalam bentuk Huruf',
            'email.unique' => 'Email ini sudah dipakai oleh pengguna lain',
            'password.min' => 'Password Minimal memiliki 8 Angka atau Huruf Panjang',
            'confirm_password.same' => 'Konfirmasi Password Salah, Mohon isi ulang'
        ]);

        $exclude = ['confirm_password'];
        if ($validated['password'] == null | $validated == ''){
            $exclude[] = 'password';
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        User::where('role', 'siswa')->where('id', $id)->update(Arr::except($validated, $exclude));

        return redirect()->route('siswa.akun.index')->with('success', 'Akun siswa berhasil untuk diperbarui');
    }
    
    public function delete($id){
        User::where('role', 'siswa')->where('id',  $id)->update([
            'nama' => null,
            'email' => null,
            'status' => 'tidak aktif'
        ]);
        
        siswa::where('id_akun', $id)->update([
            'id_akun' => null,
        ]);
        return redirect()->route('siswa.akun.index')->with('success', 'Akun Siswa berhasil untuk dihapus');
    }
}
