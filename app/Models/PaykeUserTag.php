<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaykeUserTag extends Model
{
    public function PaykeUsers()
    {
        return $this->hasMany('App\Models\PaykeUser');
    }

    protected $fillable = [
        'name'
        ,'color'
        ,'order_no'
        ,'is_hidden'
    ];

    const validation_rules = [
        'tag_name' => 'required'
    ];

    public function color()
    {
        if(!$this->color || $this->color == "none") return "grey";
        return $this->color;
    }

    public function to_array()
    {
        return ["name" => $this->name, "order_no" => $this->order_no, "is_hidden" => $this->is_hidden, "color" => $this->color];
    }
}
