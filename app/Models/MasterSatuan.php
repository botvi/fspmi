<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSatuan extends Model
{
    use HasFactory;
    protected $table = 'master_satuans';
    protected $fillable = ['nama_satuan'];
}
