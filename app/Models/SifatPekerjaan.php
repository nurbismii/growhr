<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifatPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'sifat_pekerjaan';
    protected $guarded = [];
}
