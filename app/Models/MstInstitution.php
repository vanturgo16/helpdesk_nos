<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstInstitution extends Model
{
    use HasFactory;
    protected $table = 'mst_institution';
    protected $guarded = [
        'id'
    ];
}
