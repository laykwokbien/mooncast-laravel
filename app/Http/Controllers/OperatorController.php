<?php

namespace App\Http\Controllers;

use App\Models\User;
use Arr;
use Hash;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\VarDumper\Caster\RedisCaster;
use function PHPUnit\Framework\isNull;

class OperatorController extends Controller
{
    function index(Request $request){
        $search = $request->input('search');
        $status = $request->input('status');
        if ($status === '' | $status === null){
            $status = 'aktif';
        }
        $data = User::when($search, function($query, $search) {
            $query->where('nama', 'like', "%{$search}%");
        })->where('role', 'operator')->where('status', $status)->paginate(10);
        return view('operator.index', compact('data'));
    }

    function create_page(){
        return view('operator.create');
    }

    function create(Request $request){
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => ['required','string', function ($attributes, $value, $fail){
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
            },'unique:users,email'],
        ], [
            'nama.required' => 'Baris Nama Wajib untuk diisi',
            'nama.string' => 'Baris Nama harus menggunakan huruf',
            'email.required' => 'Baris Email Wajib untuk diisi',
            'email.unique' => 'Baris Email Wajib untuk direset'
        ]);

        $password = Str::random(8);

        $data = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => bcrypt($password),
            'default_password' => $password,
            'role' => 'operator'
        ];

        $result = User::create($data);

        return redirect('/operator')->with('success', 'Akun operator baru berhasil untuk dibuat');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.' => 'exists:users,id',
        ], [
            'user_ids.required' => 'Tidak ada operator yang dipilih, Silakan Pilih salah satu checkbox',
            'user_ids.array' => 'Operator yang dipilih harus dalam bentuk array',
            'user_ids.exists' => 'Akun Operator tidak dikenali, pilihlah operator yang sudah terdaftar' 
        ]);

        $ids = $request->input('user_ids');

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user){
            $default = Str::random(8);
            User::where('role', 'operator')->where('id', $user->id)->update([
                'password' => bcrypt($default),
                'default_password' => $default
            ]);
        }

        return back()->with('success', 'Akun Berhasil untuk Direset');
    }

    function update_page($id){
        $data = User::find($id);
        return view('operator.update', compact('data'));
    }

    function update($id, Request $request){
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => ['required','string', function ($attributes, $value, $fail){
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
            'password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|string|in:'.$request->input('password'),
        ], [
            'nama.required' => 'Baris Nama Wajib untuk diisi',
            'nama.string' => 'Baris Nama harus menggunakan huruf',
            'email.required' => 'Baris Email Wajib untuk diisi',
            'email.unique' => 'Baris Email Wajib untuk direset',
            'password.string' => 'Password Harus dalam bentuk string',
            'password.min' => 'Password minimal memiliki 8 karakter',
            'confirm_password.string' => 'Konfirmasi Password harus dalam bentuk string',
            'confirm_password.in' => 'Password tidak sama, silakan ulangi lagi',
        ]);

        if ($validated['password'] != null){
            $validated['password'] = bcrypt($validated['password']);
            
            $data = [
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'operator'
            ];

            User::where('id', $id)->update($data);
        } else {
            $data = [
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'role' => 'operator'
            ];
            User::where('id', $id)->update($data);
        }

        return redirect('/operator')->with('success', 'Akun Operator berhasil untuk diperbarui');
    }

    function delete($id){
        User::where('id', $id)->update([
            'status'=> 'tidak aktif',
        ]);

        return redirect('/operator')->with('success', 'Akun Operator berhasil untuk dihapus');
    }
}
