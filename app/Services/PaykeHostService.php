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

    public function find_all_to_array(): array
    {
        $dbs = PaykeHost::orderBy("name")->get();
        return array_map(function($x){
            return ["id" => $x['id'], "name" => $x['name']];
        }, $dbs->toarray());
    }
}