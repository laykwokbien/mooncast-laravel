<?php

namespace App\Imports;

use App\Models\jurusan;
use App\Models\siswa;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaDataImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithMapping
{
    use SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function map($row): array {
        if ($row['tahun_lulus'] == '' || $row['tahun_lulus'] == null) $row['tahun_lulus'] = 0;
        $data = [];
        foreach ($row as $key => $value){
            $key = str_replace(' ', '_', strtolower($key));
            $data[$key] = $value;
        }
        return [
            'nis' => $data['nis'] ?? null,
            'nama_siswa' => $data['nama_siswa'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'tanggal_lahir' => $this->parseDate($data['tanggal_lahir'] ?? null),
            'tahun_masuk' => $data['tahun_masuk'] ?? null,
            'tahun_lulus' => $data['tahun_lulus'] ?? null,
        ];
    }

    private function parseDate($value){
        try {
            if (is_numeric($value)){
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } else {
                return Carbon::parse($value)->format('Y-m-d');
            }
        } catch(\Exception $e){
            return null;
        }
    }

    public function rules(): array
    {   
        return [
            '*.nis' => ['required', 'unique:siswas,nis'],
            '*.nama_siswa' => ['required', 'string'],
            '*.jurusan' => ['required', 'string'],
            '*.tahun_masuk' => ['required', 'integer', 'digits:4'],
            '*.alamat' => ['required', 'string'],
            '*.tahun_lulus' => ['nullable', function ($attributes, $value, $fail){
                $not_numeric = !is_numeric($value);
                if ($not_numeric == false) $not_numeric = true;
                if ($not_numeric == true) $not_numeric = false; 
                if ((int)$value == 0) {
                    return;
                } else if ($not_numeric && strlen($value) != 4)
                {
                    $fail('Tahun Lulus harus 4 digits');
                }
            }, 'regex:/^[0-9]+$/'],
            '*.tanggal_lahir' => ['required', 'date']
        ];
    }
    public function model(array $row)
    {
        $jurusan = jurusan::where('nama_jurusan', $row['jurusan'])->first();
        
        return new siswa([
            'nis' => $row['nis'],
            'nama_siswa' => $row['nama_siswa'],
            'alamat' => $row['alamat'],
            'jurusan' => $jurusan?->id,
            'tanggal_lahir' => $row['tanggal_lahir'],
            'tahun_masuk' => $row['tahun_masuk'],
            'tahun_lulus' => $row['tahun_lulus'],
        ]);
    }

    public function onFailure(Failure ...$failures){
        $this->failures = $failures;
    }
}
