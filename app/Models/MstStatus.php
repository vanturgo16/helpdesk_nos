<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstStatus extends Model
{
    use HasFactory;
    protected $table = 'mst_status';
    protected $guarded = [
        'id'
    ];
}
