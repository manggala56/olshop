<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class laporan_penjualan extends Model
{
    protected $table = 'laporan_penjualans';
    protected $fillable = ['nama_laporan', 'tanggal_laporan', 'jumlah_laporan', 'keterangan_laporan', 'status_laporan', 'kategori_laporan', 'opsional', 'addon', 'platform'];
    protected $casts = [
        'tanggal_laporan' => 'date',
    ];
    public function getTanggalLaporanAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
