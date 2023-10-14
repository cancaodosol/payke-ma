<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeDb extends Model
{
    use HasFactory;

    public function PaykeUser()
    {
        return $this->hasOne('App\Models\PaykeUser');
    }
}
