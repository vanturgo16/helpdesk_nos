<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCategory extends Model
{
    use HasFactory;
    protected $table = 'mst_category';
    protected $guarded = [
        'id'
    ];
}
