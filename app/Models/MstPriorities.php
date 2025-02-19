<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstPriorities extends Model
{
    use HasFactory;
    protected $table = 'mst_priority';
    protected $guarded = [
        'id'
    ];
}
