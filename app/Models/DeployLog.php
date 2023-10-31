<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployLog extends Model
{
    use HasFactory;

    const TYPE__VERSION_INFO = 0;
    const TYPE__WARM = 1;
    const TYPE__ERROR = 2;
    const TYPE__OTHER_INFO = 9;

    public function is_version_info() { return $this->type == DeployLog::TYPE__VERSION_INFO; }
    public function is_warm() { return $this->type == DeployLog::TYPE__WARM; }
    public function is_error() { return $this->type == DeployLog::TYPE__ERROR; }
    public function is_other_info() { return $this->type == DeployLog::TYPE__OTHER_INFO; }

    public function getLogArray() : array
    {
        if($this->deployer_log == "") return [];
        return explode("\n", $this->deployer_log);
    }

    public function getDiffTime() : string
    {
        $th = new TimeHelper();
        $now = time();
        return $th->to_diff_string($now, strtotime($this->created_at));
    }
}
