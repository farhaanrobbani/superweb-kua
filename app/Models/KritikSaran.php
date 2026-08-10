<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'kontak', 'kategori', 'isi'])]
class KritikSaran extends Model
{
    use HasFactory;

    public const KATEGORI = [
        'Pelayanan',
        'Sarana & Prasarana',
        'Sikap Petugas',
        'Saran Umum',
    ];
}
