<?php

namespace App\Services;

use App\Models\PaykeHost;

class PaykeHostService
{
    public function find_all()
    {
        return PaykeHost::with(['PaykeDbs' => function($db) {
            $db->orderBy('db_database','asc');
        }])->get();
    }
}