<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeUser extends Model
{
    use HasFactory;

    public function PaykeHost()
    {
        return $this->belongsTo('App\Models\PaykeHost');
    }

    public function PaykeDb()
    {
        return $this->belongsTo('App\Models\PaykeDb');
    }

    public function PaykeResource()
    {
        return $this->belongsTo('App\Models\PaykeResource');
    }
}
