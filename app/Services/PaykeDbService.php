<?php

namespace App\Services;

use App\Models\PaykeDb;
use App\Models\PaykeHost;

class PaykeDbService
{
    public function find_all()
    {
        return PaykeDb::all();
    }

    public function find_by_id(int $id)
    {
        return PaykeDb::where('id', $id)->firstOrFail();
    }

    public function find_ready_host_dbs($host_id = null)
    {
        $host_dbs = [];
        $hosts = $host_id == null ? PaykeHost::orderBy('id', 'ASC')->get() : PaykeHost::where('id', $host_id)->get();
        foreach ($hosts as $host) {
            $dbs = PaykeDb::where(([
                ['payke_host_id', '=', $host->id],
                ['status', '=', PaykeDb::STATUS__READY],
              ]))->orderBy('db_database','asc')->get();
            foreach ($dbs as $db) {
                array_push($host_dbs, [
                    "id" => "{$host->id}_{$db->id}",
                    "host_id" => "{$host->id}",
                    "db_id" => "{$db->id}",
                    "name" => "{$host->name} / {$db->db_database}"
                ]);
            }
        }
        return $host_dbs;
    }

    public function get_statuses()
    {
        $statuses = [
            ["id" => PaykeDb::STATUS__READY, "name" => "空き"],
            ["id" => PaykeDb::STATUS__IN_USE, "name" => "使用中"],
            ["id" => PaykeDb::STATUS__DELETE, "name" => "削除済"]
        ];
        return $statuses;
    }

    public function add(PaykeDb $db)
    {
        $db->status = PaykeDb::STATUS__READY;
        $db->save();
    }

    public function edit(int $id, array $values)
    {
        $db = $this->find_by_id($id);
        $db->update($values);
    }
}