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

    public function find_by_id(int $id)
    {
        return PaykeHost::where('id', $id)->firstOrFail();
    }

    public function get_statuses()
    {
        $statuses = [
            ["id" => PaykeHost::STATUS__READY, "name" => "使用可"],
            ["id" => PaykeHost::STATUS__DELETE, "name" => "削除済"]
        ];
        return $statuses;
    }

    public function add(PaykeHost $host)
    {
        $host->status = PaykeHost::STATUS__READY;
        $host->save();
    }

    public function edit(int $id, array $values)
    {
        $host = $this->find_by_id($id);
        $host->update($values);
    }
}