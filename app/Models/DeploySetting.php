<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeploySetting extends Model
{
    protected $fillable = [
        'key'
        ,'value'
        ,'no'
    ];

    public function set_no(int $no)
    {
        $this->no = $no;
        return $this;
    }

    public function set_key_and_value(string $key, $value): DeploySetting
    {
        $this->key = $key;
        $this->value = $value;
        return $this;
    }
}
