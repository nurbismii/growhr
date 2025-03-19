<?php

namespace App\Models\Hris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartemenHris extends Model
{
    use HasFactory;

    protected $connection = 'hris';
    protected $table = 'departemens';

    protected $guarded = [];
}
