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
        $r1 = $zh->get_payke_version("./payke_resources/zips/payke-ec-cae6ae8bf6d3.zip");
        $this->assertEquals("v3.22.2", $r1);

        $r2 = $zh->get_payke_version("./payke_resources/zips/payke-ec-752d7ee2ff92.zip");
        $this->assertEquals("v3.21.7.1", $r2);
    }
}