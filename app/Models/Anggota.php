<?php

namespace App\Models;

use App\Traits\GenUid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory, GenUid;

    protected $fillable = [
        'nama',
        'email',
        'umur',
        'status',
    ];
}
