<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function isJurusan()
    {
        return $this->hasOne(jurusan::class, 'id', 'jurusan');
    }

    public function isProximo(){
        return $this->hasOne(ProximoPeriod::class, 'nis', 'nis');
    }

    public function AllData(){
        return $this->hasMany(Bulan::class, 'nis', 'nis');
    }

    public function hasAccount(){
        return $this->belongsTo(User::class, 'id_akun', 'id');
    }

    public function isPred(){
        return $this->hasMany(prediction::class, 'nis', 'nis');
    }

    public function allRealtimeData(){
        return $this->hasMany(realtime::class, 'nis', 'nis');
    }

    public function getKelasAttribute(){
        $semester = $this->isProximo->semester;
        $kelas = match (true){
            $semester >= 5 => 'Kelas 12',
            $semester >= 3 => 'Kelas 11',
            $semester >= 1 => 'Kelas 10',
            default => 'Belum Didata',
        };

        return $kelas;
    }

    public function getIsPeriodAttribute(){
        $predictions = $this->isPred()->whereNot('month_index', 0)->get();

        if (!$predictions || count($predictions) == 0) return ["haid" => false, 'message' => 'Tidak', 'start' => 'Tidak Ada', 'end' => 'Tidak Ada', 'next' => 'Tidak Ada'];
        for ($i = 0;$i < count($predictions);$i++){
            $start = Carbon::parse($predictions[$i]->tanggal_mulai_haid);
            $end = $start->copy()->addDays($predictions[$i]->durasi_haid - 1);
            $next = Carbon::parse($predictions[$i + 1]->tanggal_mulai_haid);
            $nextend = $next->copy()->addDays($predictions[$i + 1]->durasi_haid -1);
            if (now() < $start){
                return [
                    "haid" => false, 
                    "message" => "Tidak", 
                    'start' => 'tidak ada', 
                    'end' => 'Tidak Ada', 
                    'next' => $next->format('d M Y'),
                    'nextend' => $nextend->format('d M Y')
                ];
            }
    
            if (now()->between($start, $end)){
                return [
                    "haid" => true, 
                    "message" => "Ya", 
                    'start' => $start->format('d M Y'), 
                    'end' => $end->format('d M Y'), 
                    'next' => $next->format('d M Y'),
                    'nextend' =>$nextend->format('d M Y')
                ];
            }
        }
        return ["haid" => false, "message" => "Tidak", 'start' => 'Tidak Ada', 'end' => 'Tidak Ada', 'next' => 'Tidak Ada'];
    }

    public function getHasPeriodsAttribute(){
        $result = $this->isPred()->get();
        return count($result) != 0;
    }

    public function getTodayPeriodAttribute(){
        $prediction = $this->isPred()->where('month_index', 0)->first();

        if ($prediction == null || $prediction == '') return ['haid' => false, 'message' => 'Tidak', 'start' => 'Tidak Ada', 'end' => 'Tidak Ada'] ;
        
        $start = Carbon::parse($prediction->tanggal_mulai_haid);
        $end = $start->copy()->addDays($prediction->durasi_haid);
        if (now()->between($start, $end)){
            return ['haid' => now()->between($start, $end), 'message' => 'Ya', 'start' => $start->format('d M Y'), 'end' => $end->format('d M Y')];
        }
        return ['haid' => false, 'message' => 'Tidak', 'start' => 'Tidak Ada', 'end' => 'Tidak Ada'] ;
    }
}
