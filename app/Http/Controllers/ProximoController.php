<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use App\Models\siswa;
use Arr;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Prediction;

class ProximoController extends Controller
{
    function index(Request $request){
        $search = $request->input('search');

        $data = siswa::whereHas('isProximo')->when($search, function ($q, $search){
            $q->where('nama_siswa', 'like', "%{$search}%");
        })->with('isPred', 'isProximo', 'isJurusan')->paginate(10);

        return view('prediction.multi.index', data: compact('data'));
    }
    
    function show($id){
        $detail = siswa::with(['isPred', 'isProximo', 'isJurusan'])->find($id);        

        return back()->with('detailPred', $detail->toArray());
    }

    function retrain(){
        try{
            Http::timeout(5)->get('http://localhost:5000/retrain');
        } catch (Exception $e){
            return back()->with('success', 'Traning Model In Progress Please Wait');
        }
    }

    function checkStatus(){
        $response = Http::timeout(5)->get('http://localhost:5000/status')->json();

        return back()->with('success', "Status Pelatihan Model: {$response['Traning']}");
    }
}
