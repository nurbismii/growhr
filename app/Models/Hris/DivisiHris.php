<?php

namespace App\Models\Hris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisiHris extends Model
{
    use HasFactory;

    protected $connection = 'hris';
    protected $table = 'divisis';

    protected $guarded = [];

    public function getDepartemen()
    {
        return $this->hasOne(DepartemenHris::class, 'id', 'departemen_id');
    }
}
