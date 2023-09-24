<?php

namespace Tests\Unit\Services;

use App\Services\DeployService;
use PHPUnit\Framework\TestCase;

class DeployServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_test(): void
    {
        $ds = new DeployService();
        $this->assertEquals($ds->test(), 'ok!');
    }
}
