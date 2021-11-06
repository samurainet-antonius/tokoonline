<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    use HasFactory;

    protected $table = 'merk';

    protected $fillable = [
        'nama_merk',
        'url_merk',
        'created_at',
        'updated_at',
        'uuid'
    ];

    protected $hidden = [
        'id'
    ];
}
