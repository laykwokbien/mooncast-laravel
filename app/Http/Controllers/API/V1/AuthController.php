<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\siswa;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Str;

class AuthController extends Controller
{
    public function checkApiKey(Request $request){
        $api = $request->input("api_key");
        if (User::where('api_token', hash('sha256', $api))->exists()){
            return response()->json([
                "authorized" => true,
                "msg" => "Berhasil untuk Login"
            ], status:200);
        }
        return response()->json(
            [
                "authorized" => false,
                "msg" => "Kredensial tidak dikenal"
            ], status:401);
    }
    public function login(Request $request){
        $validated = $request->validate([
            "nama" => "required|string",
            "password" => "required|min:8"
        ]);
        $validated["role"] = "siswa";
        if (Auth::attempt($validated)){
            $user = Auth::user()->load('isData.isProximo');

            if ($user->role !== "siswa" || $user->status !== "aktif"){
                return response()->json([
                    "msg" => "Kredensial tidak valid, mohon coba ulang"
                ], status: 401);
            }

            do {
                $token = Str::random(60);
                $hashed = hash("sha256", $token);
            } while (User::where('api_token', $hashed)->exists());

            $user->api_token = $hashed;
            $user->save();

            return response()->json([
                "msg" => "Berhasil untuk login",
                "access_token" => $token,
                "user" => [
                    "nis" => $user->isData->nis,
                    "username" => $user->nama,
                    "email" => $user->email,
                    "alamat" => $user->isData->alamat,
                    "tanggal_lahir" => $user->isData->tanggal_lahir,
                    "jurusan" => $user->jurusan_name,
                ],
            ], status: 200);
        }
        return response()->json([
            "msg" => "Kredensial tidak valid, mohon coba ulang"
        ], status: 401);
    }
    
    public function logout(Request $request){
        if (!User::where('api_token', $request->input('api_token'))->exists){
            return response()->json([
                "msg" => "Logout Gagal, Mohon coba ulang atau hubungi pihak yang bersangkutan",
            ], status: 403);
        }
        User::where('api_token', $request->input('api_token'))->update([
            "api_token" => null,
        ]);

        return response()->json([
            "msg" => "Berhasil untuk Logout"
        ], status:200);
    }

    public function setSurveyState(Request $request){
        $token = $request->input("api_token");
        $survey = $request->input("survey");
        $q = User::where('api_token', hash("sha256", $token));
        if (!$q->exists()){
            return response()->json([
                "msg" => "User not Found"
            ], status:401);
        }

        if (gettype($survey) != 'boolean'){
            return response()->json([
                "msg" => "Abnormal Input Error, Coba Ulangi lagi",
            ], status:422);
        }

        $q->update([
            'survey' => $survey
        ]);

        return response()->json([
            "msg" => "Berhasil untuk mengubah",
        ], status:200);

    }
    
    public function getSurveyState(Request $request){
        $token = $request->input("api_token");
        $query = User::where("api_token", hash('sha256', $token));
        if (!$query->exists()){
            return response()->json([
                "msg" => "User tidak ditemukan, mohon coba lagi"
            ], status: 422);
        }

        $survey = (bool)$query->first()->survey;

        return response()->json([
            "survey" => $survey,
            "msg" => "Berhasil untuk menerima Survey State",
        ], status:200);
    }
    
    public function updateData(Request $request){
        $data = $request->only(["nama", "email", "alamat", "tanggal_lahir", "new_password"]);
        $auth = $request->only(["password", "api_token"]);
        if (!$request->exists("password")){
            return response()->json([
                    "status" => "fail",
                    "msg" => "Mohon isi password Anda untuk mengubah!"
                ], status: 422);
        }
        if (strlen($data["new_password"]) < 8){
            return response()->json([
                    "status" => "fail",
                    "msg" => "Password Baru minimal memiliki 8 karakter"
                ], status: 422);
        }
        $auth["api_token"] = hash("sha256", $auth["api_token"]);
        if (Auth::attempt($auth)){
            $user = Auth::user();
            $user->nama = $data["nama"] ?? $user->nama;
            $user->email = $data["email"] ?? $user->nama;
            if ($data["new_password"] != null){
                $user->password = bcrypt($data["new_password"]);
            }
            $siswa = siswa::where('id_akun', $user->id);
            if ($data["tanggal_lahir"] != null){
                $data["tanggal_lahir"] = strtotime($data["tanggal_lahir"]);
            }
            $siswa->update([
                "alamat" => $data["alamat"] ?? $siswa->first()->alamat,
                "tanggal_lahir" => $data["tanggal_lahir"] ?? $siswa->first()->tanggal_lahir,
            ]);
            $user->save();
            return response()->json([
                "status" => "success",
                "msg" => "Data Berhasil untuk diperbarui"
            ], status:200);
        }
        return response()->json([
            "status" => "fail",
            "msg" => "Kredensial tidak dikenal, Mohon coba lagi"
        ], status:401);
    }
}
