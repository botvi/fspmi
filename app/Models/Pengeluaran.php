<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'pengeluarans';
    protected $fillable = ['user_id', 'tanggal_keluar', 'keterangan', 'harga_satuan', 'jumlah', 'master_satuan_id', 'total_harga', 'gambar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function master_satuan()
    {
        return $this->belongsTo(MasterSatuan::class);
    }
}
