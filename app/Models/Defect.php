<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Defect extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika bukan nama default
    protected $table = 'defects';

    // Tentukan kolom yang dapat diisi massal
    protected $fillable = [
        'tanggal', 'cell', 'idpass', 'qtyok', 'qtynok', 'defect', 'images'
    ];

    protected $casts = [
        'idpass' => 'array',
        'qtyok' => 'array',
        'qtynok' => 'array',
        'images' => 'array',
    ];

    // Jika menggunakan timestamps, secara default Laravel akan mengelola kolom created_at dan updated_at
    public $timestamps = true;
}