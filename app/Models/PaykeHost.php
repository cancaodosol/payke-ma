<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaykeHost extends Model
{
    use HasFactory;
    public function PaykeDbs()
    {
        return $this->hasMany('App\Models\PaykeDb');
    }
}
