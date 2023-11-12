<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaykeHost extends Model
{
    use HasFactory;

    const STATUS__READY = 0;
    const STATUS__DELETE = 1;

    public function PaykeDbs()
    {
        return $this->hasMany('App\Models\PaykeDb');
    }

    protected $fillable = [
        'status'
        ,'name'
        ,'hostname'
        ,'remote_user'
        ,'port'
        ,'identity_file'
        ,'resource_dir'
        ,'public_html_dir'
    ];

    const validation_rules = [
        'name' => 'required'
        ,'hostname' => 'required'
        ,'remote_user' => 'required'
        ,'port' => 'required'
        ,'identity_file' =>'required'
        ,'resource_dir' => 'required'
        ,'public_html_dir' => 'required'
    ];
}
