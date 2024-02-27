<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeEcOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid'
        ,'order_id'
        ,'type'
        ,'raw'
        ,'raw_created_at'
    ];
}
