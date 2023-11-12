<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeResource extends Model
{
    use HasFactory;

    public function PaykeUsers()
    {
        return $this->hasMany('App\Models\PaykeUser');
    }
 
    // versionは、v3.21.7のカタチを想定。
    // MEMO : varison_zが 7.1とかの場合は、画面から修正してもらう。
    public function set_version(string $version): void
    {
        $vs = explode(".", $version);
        $vx = substr($vs[0], 1, strlen($vs[0])-1);
        $vy = $vs[1];
        $vz = $vs[2];

        $this->version = $version;
        $this->version_x = $vx;
        $this->version_y = $vy;
        $this->version_z = $vz;
    }

    // MEMO : "."は、アプリのURLに使えないため、"-"に置換する。
    public function set_name(string $version): void
    {
        $this->payke_name = "payke-ec_".str_replace('.', '-', $version);
    }

    protected $fillable = [
        'version'
        ,'version_x'
        ,'version_y'
        ,'version_z'
        ,'payke_name'
        ,'payke_zip_name'
        ,'payke_zip_file_path'
        ,'memo'
    ];

    public function diff_time_from_now() : string
    {
        $th = new TimeHelper();
        $now = time();
        return $th->to_diff_string($now, strtotime($this->created_at));
    }
}
