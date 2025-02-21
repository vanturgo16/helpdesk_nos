<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTicket extends Model
{
    use HasFactory;
    protected $table = 'log_tickets';
    protected $guarded = [
        'id'
    ];
}
