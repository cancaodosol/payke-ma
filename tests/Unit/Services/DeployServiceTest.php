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

    /**
     * exec deploy test.
     */
    public function test_exec_deploy(): void
    {
        $ds = new DeployService();
        $p1 = [
            'payke_app_name' => 'payke05',
            'payke_zip_name' => 'payke-ec-752d7ee2ff92',
            'payke_zip_file_path' => '/payke_resources/payke-ec-752d7ee2ff92.zip',
            'payke_install_file_path' => '/payke_resources/install.php',
            'payke_env_file_path' => '/payke_resources/.env.php',
            'release_path' => 'hiderin.xyz/public_html'
        ];
        $o1 = $ds->exec_deply($p1);
        print_r($o1);
        // $this->assertEquals($o1[0], 'rsync  version 3.2.7  protocol version 31');
    }
}
