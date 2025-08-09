<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class laporan_keuangan extends Model
{
    protected $table = 'laporan_keuangans';
    protected $fillable = ['nama_laporan', 'tanggal_laporan', 'jumlah_laporan', 'keterangan_laporan', 'status_laporan', 'kategori_laporan', 'opsional', 'addon'];
    protected $casts = [
        'tanggal_laporan' => 'date',
    ];
    public function getTanggalLaporanAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
