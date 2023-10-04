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
    }

    /**
     * create .env.php test.
     */
    public function test_create_env_file(): void
    {
        $ds = new DeployService();
        $n1 = 'new';
        $c_ori = [
            'DB_DATASOURCE' => 'Database/Mysql',
            'DB_HOST' => 'localhost',
            'DB_PORT' => 3306,
            'DB_DATABASE' => 'hirotae_payma02',
            'DB_USERNAME' => 'hirotae_h1de',
            'DB_PASSWORD' => 'matsui1234',
            'DB_PREFIX' => ''
        ];
        $c1 = [
            'DB_DATASOURCE' => 'でーたりそーす',
            'DB_HOST' => 'ろーかるほすと',
            'DB_PORT' => 1234,
            'DB_DATABASE' => 'でーたべーす',
            'DB_USERNAME' => 'ゆ〜ざ〜',
            'DB_PASSWORD' => 'ぱす',
            'DB_PREFIX' => 'ぷれふぃっくす'
        ];
        $o1 = $ds->create_env_file($n1, $c1);
        print_r($o1);
    }
}
