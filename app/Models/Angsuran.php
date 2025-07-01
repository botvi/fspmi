<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;
    protected $table = 'angsurans';
    protected $fillable = ['pinjaman_id', 'member_id', 'jumlah_angsuran', 'tanggal_angsuran'];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }
    
}
