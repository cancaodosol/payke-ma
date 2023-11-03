<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeResource extends Model
{
    use HasFactory;
 
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
}
