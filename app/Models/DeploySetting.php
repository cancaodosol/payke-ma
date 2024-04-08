<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key'
        ,'value'
    ];

    public function set_key_and_value(string $key, string $value): DeploySetting
    {
        $this->key = $key;
        $this->value = $value;
        return $this;
    }
}
