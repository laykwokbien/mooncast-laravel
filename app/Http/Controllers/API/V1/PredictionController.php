<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\prediction;
use App\Models\ProximoPeriod;
use App\Models\siswa;
use App\Models\User;
use Arr;
use Carbon\Carbon;
use Exception;
use Http;
use Illuminate\Http\Request;
class PredictionController extends Controller
{
    public function setup (Request $request){
        $token = $request->input('api_token');
        $data = $request->only(["tanggal_mulai", "umur", "semester", "suhu_tubuh", "aktivitas_fisik", "stress", "masalah_kesehatan", "rokok", "obat"]);
        $error = [];

        $token = User::where('api_token', Hash("sha256",$token));
        if (!$token->exists()){
            return response()->json([
                "msg" => "Invalid User Session"
            ], status: 401);
        }
        try {
            $nis = siswa::where('id_akun', $token->first()->id)->first()->nis;
        } catch (Exception $e){
            return response()->json([
                "msg" => $e,
            ], status: 500);
        }

        foreach ($data as $k => $v){

            if ($v == "" || $v == null){
                $error[$k] = "Kolom $k tidak boleh kosong atau null";
            } else if ($k == "tanggal_mulai") {
                try {
                    $data[$k] = Carbon::parse($data[$k]);
                    $data["tahun"] = $data[$k]->year;
                    $data["bulan"] = $data[$k]->month;
                } catch (Exception $e){
                    $error[$k] = "Format Tanggal tidak valid";
                }
            } else if ($k == "suhu_tubuh"){
                try {
                    $data[$k] = (int) $data[$k];
                } catch (Exception $e){
                    $error[$k] = "Bukan berbentuk numerik ataupun bilangan bulat";
                }
            } else if (in_array($k,["masalah_kesehatan", "rokok", "obat"])){
                $accepted = ["ya", "tidak"];
                if (!in_array(strtolower($v), $accepted)){
                    $error[$k] = "Nilai yang hanya bisa diisi adalah ${join(', ', $accepted)}";
                } else {
                    $data[$k] = strtolower($v);
                }
            } else if ($k == "stress"){
                $accepted = ["tidak", "rendah", "sedang", "tinggi"];
                if (!in_array(strtolower($v), $accepted)){
                    $error[$k] = "Nilai yang bisa diterima adalah ${join(', ', $accepted)}";
                } else {
                    $data[$k] = strtolower($v);
                }
            } else if ($k == "aktivitas_fisik"){
                $accepted = ["tidak", "ringan", "sedang", "berat"];
                if (!in_array(strtolower($v), $accepted)){
                    $error[$k] = "Nilai yang bisa diterima adalah ${join(', ', $accepted)}";
                } else {
                    $data[$k] = strtolower($v);
                }
            }
        }
        if (count($error) != 0){
            return response()->json([
                "msg" => "Invalid Input",
                "error" => $error,
            ], status: 422);
        }

        $data["t"] = 1;

        $response = Http::post("http://192.168.52.12:5000/predict", Arr::only($data, ["bulan", "semester", "umur", "suhu_tubuh", "aktivitas_fisik", "stress", "rokok", "masalah_kesehatan", "obat", "tahun", "t"]));
        
        $data['nis'] = $nis;

        ProximoPeriod::updateOrCreate(Arr::only($data, ['nis', 'umur', 'semester', 'suhu_tubuh', 'aktivitas_fisik', 'stress', 'masalah_kesehatan', 'rokok', 'obat']));

        $pred = [];
        
        $cycle_length = $response["durasi_siklus"];
        $mens_length = $response["durasi_haid"];

        $average_cycle = round(array_sum($cycle_length) / count($cycle_length)); 
        $average_mens = round(array_sum($mens_length) / count($mens_length)); 

        $date = $data['tanggal_mulai'];

        for ($i = 0; $i < count($cycle_length); $i++){
            $start = $date->addDays($cycle_length[$i]);
            $durasi = $cycle_length[$i];
            $pred[$i] = ['tanggal_mulai_haid' => $start->format("Y-m-d"), "durasi_haid" => $durasi];
            prediction::updateOrCreate(["nis" => $nis, "month_index" => $i], $pred[$i]);
        }
        
        for ($i = count($cycle_length) - 1; $i < 13; $i++){
            $start = $date->addDays($average_cycle);
            $durasi = $average_mens;
            $pred[$i] = ['tanggal_mulai_haid' => $start->format("Y-m-d"), "durasi_haid" => $durasi];
            prediction::updateOrCreate(["nis" => $nis, "month_index" => $i], $pred[$i]);
        }
        
        return response()->json([
            "msg" => "Berhasil untuk memprediksi",
            "prediction" => $pred, 
        ], status: 200);
    }

    public function getPrediction(Request $request){
        $token = $request->input('api_token');

        $user = User::where('api_token', Hash("sha256",$token));
        if (!$user->exists()){
            return response()->json([
                "msg" => "Kredensial yang tidak dikenal"
            ], status: 401);
        }
        
        $nis = $user->first()->isData->nis;
        
        if (!ProximoPeriod::where('nis', $nis)->exists() && !prediction::where('nis', $nis)->exists()){
            return response()->json([
                "msg" => "Data tidak ditemukan mohon coba lagi"
            ], status: 404);
        }

        $proximo = ProximoPeriod::where('nis', $nis)->first()->toArray();
        $pred = prediction::where('nis', $nis)->get()->toArray();

        $result = [];

        for ($i = 0; $i < count($pred); $i++){
            $result[] = Arr::only($pred[$i], ["tanggal_mulai_haid", "durasi_haid"]);
        }


        return response()->json([
            "msg" => "Berhasil untuk mendapatkan prediksi",
            "data" => Arr::except($proximo, ["id", "nis", "updated_at", "created_at"]),
            "prediction" => $result,
        ], status:200);
    }
}
