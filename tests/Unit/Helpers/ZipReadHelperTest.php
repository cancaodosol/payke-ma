<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ZipReadHelper;
use PHPUnit\Framework\TestCase;

class ZipReadHelperTest extends TestCase
{
    /**
     * exec test.
     */
    public function test_exec(): void
    {
        $zh = new ZipReadHelper();
        $r1 = $zh->read_payke_version("./payke_resources/zips/payke-ec-cae6ae8bf6d3.zip");
        $this->assertEquals("v3.22.2", $r1);

        $r2 = $zh->read_payke_version("./payke_resources/zips/payke-ec-752d7ee2ff92.zip");
        $this->assertEquals("v3.21.7.1", $r2);

        $vs = explode(".", $r2);
        $vx = substr($vs[0], 1, strlen($vs[0])-1);
        $vy = $vs[1];
        $vz = $vs[2];
        print("{$r2} ---> x {$vx}  y {$vy}  z {$vz}");

        $r3 = $zh->read_payke_version("./storage/app/payke_resources/zips/payke-ec-6d979f64ed30.zip");
        $this->assertEquals("v3.23.1", $r3);
    }
}