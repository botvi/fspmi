<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;
    protected $table = 'pinjaman';
    protected $fillable = ['member_id', 'keterangan', 'jumlah_pinjaman'];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

    public function angsurans()
    {
        return $this->hasMany(Angsuran::class);
    }

    // Method untuk mendapatkan total pinjaman per member
    public static function getTotalPinjamanByMember($memberId)
    {
        return self::where('member_id', $memberId)->sum('jumlah_pinjaman');
    }

    // Method untuk mendapatkan total angsuran per member
    public static function getTotalAngsuranByMember($memberId)
    {
        return Angsuran::whereHas('pinjaman', function($query) use ($memberId) {
            $query->where('member_id', $memberId);
        })->sum('jumlah_angsuran');
    }

    // Method untuk mendapatkan sisa pinjaman per member
    public static function getSisaPinjamanByMember($memberId)
    {
        $totalPinjaman = self::getTotalPinjamanByMember($memberId);
        $totalAngsuran = self::getTotalAngsuranByMember($memberId);
        return $totalPinjaman - $totalAngsuran;
    }
}
