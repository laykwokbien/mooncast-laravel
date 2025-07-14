<?php

namespace App\Http\Controllers;

use App\Helper\Settings;
use App\Models\User;
use Arr;
use Auth;
use Hash;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnArgument;

class UserController extends Controller
{
    public function login_page(){
        return view("user.login");
    }

    public function login(Request $request){
        $cred = $request->validate([
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
            }],
            'password' => 'required|string|min:8'
        ], [
            'email.required' => 'Email Wajib untuk diisi',
            'email.string' => 'Email harus dalam bentuk string',
            'password.required' => 'Password Wajib untuk diisi',
            'password.string' => 'Password harus dalam bentuk String',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        if (Auth::attempt($cred)){
            $request->session()->regenerate();

            if (Auth::user()->role == 'siswa'){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('fail', 'Email atau Password Salah');
            }

            if (Auth::user()->status == 'tidak aktif'){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('fail', 'Akun sudah tidak aktif, Mohon Hubungi Admin atau pihak berlaku');
            }

            return redirect()->intended('/dashboard');
        }

        return redirect('/login')->with('fail', 'Email atau Password salah');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil Keluar Dari Akun');
    }

    public function settings (){
        $schedule = Settings::getScheduleSettings();
        return view('user.settings', compact('schedule'));
    }

    public function update(Request $request){
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
            }, 'unique:users,id,'. Auth::user()->id],
            'password' => 'nullable|string|min:8',
            'confirm_password' => 'nullable|same:password',
            'old_password' => 'required',
        ], [
            'nama.required' => 'Nama Wajib untuk diisi',
            'nama.string' => 'Nama harus dalam bentuk string',
            'email.required' => 'Email Wajib untuk diisi',
            'email.string' => 'Email harus dalam bentuk string',
            'password.required' => 'Password Wajib untuk diisi',
            'password.string' => 'Password harus dalam bentuk String',
            'password.min' => 'Password minimal 8 karakter',
            'confirm_password.same' => 'Password tidak sama, silakan ulangi lagi',
            'old_password.required' => 'Password Lama harus diisi'
        ]);

        if (!Hash::check($request->input('old_password'), Auth::user()->password)){
            return back()->withErrors(['old_password' => 'Password Lama Salah'])->withInput();
        }

        if ($validated['password'] == null){
            $exception = ['confirm_password', 'old_password', 'password'];
        } else {
            $validated['password'] = bcrypt($validated['password']);
            $exception = ['confirm_password', 'old_password'];
        }
        
        User::where('id', Auth::id())->update(Arr::except($validated, $exception));

        return redirect()->route('settings')->with('success', 'Setelan Akun Anda Berhasil untuk diperbarui');
    }

    public function updateSchedule(Request $request){
        $validated = $request->validate([
            'auto' => 'required|in:0,1',
            'month' => 'required|min:1|max:12',
        ], [
            'auto.required' => 'Auto Traning Wajib untuk diisi',
            'auto.in' => 'Auto Traning hanya bisa Diisi dengan Enable atau Disable',
            'month.required' => 'Bulan Traning Wajib untuk diisi',
            'month.min' => 'Bulan Training minimal diisi dengan Bulan Januari',
            'month.max' => 'Bulan Training maksimal diisi dengan Bulan Desember',
        ]);

        Settings::updateScheduleSettings($validated['auto'], $validated['month']);

        return back()->with('success', 'Setting Traning Berhasil untuk di ubah');
    }
}
