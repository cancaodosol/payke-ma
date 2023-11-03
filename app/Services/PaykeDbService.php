<?php

namespace App\Services;

use App\Models\PaykeDb;
use App\Models\PaykeHost;

class PaykeDbService
{
    public function find_all()
    {
        return PaykeDb::all()->orderBy('db_database','asc')->get();;
    }

    public function find_ready_host_dbs()
    {
        $host_dbs = [];
        $hosts = PaykeHost::orderBy('id', 'ASC')->get();
        foreach ($hosts as $host) {
            $dbs = PaykeDb::where('status', PaykeDb::STATUS__READY)->orderBy('db_database','asc')->get();
            foreach ($dbs as $db) {
                array_push($host_dbs, ["id" => "{$host->id}_{$db->id}", "name" => "{$host->name} / {$db->db_database}"]);
            }
        }
        return $host_dbs;
    }
}