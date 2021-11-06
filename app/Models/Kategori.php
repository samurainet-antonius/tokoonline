<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
        'url_kategori',
        'created_at',
        'updated_at',
        'uuid'
    ];

    protected $hidden = [
        'id'
    ];
}
