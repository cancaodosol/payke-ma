<?php

namespace Tests\Unit\Services;

use App\Services\DeployService;
use PHPUnit\Framework\TestCase;

class DeployServiceTest extends TestCase
{
    /**
     * exec test.
     */
    public function test_exec(): void
    {
        $ds = new DeployService();
        $c1 = 'rsync --version';
        $o1 = $ds->exec($c1);
        // print_r($o1);
        $this->assertEquals($o1[0], 'rsync  version 3.2.7  protocol version 31');
    }
}
