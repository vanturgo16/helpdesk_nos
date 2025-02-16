<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstSubCategory extends Model
{
    use HasFactory;
    protected $table = 'mst_sub_category';
    protected $guarded = [
        'id'
    ];
}
