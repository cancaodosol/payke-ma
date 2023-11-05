<?php

namespace Tests\Unit\Services;

use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;
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
            'host_name' => 'hiderin.xyz',
            'host_remote_user' => 'hirotae',
            'host_port' => 10022,
            'host_identity_file' => './.ssh/hideringa_xserver_rsa',
            'resource_dir' => '~/hiderin.xyz/payke_resources',
            'public_html_dir' => '~/hiderin.xyz/public_html',

            'user_folder_id' => 'user_007131',
            'user_app_name' => 'tarotaro7',
            'is_first' => '',
            'payke_install_file_path' => '/payke_resources/templates/install.php',
            'payke_env_file_path' => '/payke_resources/templates/.env.php',

            'db_host' => 'localhost',
            'db_username' => 'hirotae_h1de',
            'db_password' => 'matsui1234',
            'db_database' => 'hirotae_payma04',

            'payke_name' => 'payke-ec_v3-22-3',
            'payke_zip_name' => 'payke-ec-cae6ae8bf6d3',
            'payke_zip_file_path' => '/payke_resources/zips/payke-ec-cae6ae8bf6d3.zip',

            'deploy_datetime' => '20231010_123432'
        ];

        // $o1 = $ds->exec_deply($p1);
        // print_r($o1);
        // $this->assertEquals($o1[0], 'rsync  version 3.2.7  protocol version 31');
    }

    /**
     * exec deploy test.
     */
    public function test_exec_deploy_unlock(): void
    {
        $ds = new DeployService();

        $p1 = [
            'host_name' => 'hiderin.xyz',
            'host_remote_user' => 'hirotae',
            'host_port' => 10022,
            'host_identity_file' => './.ssh/hideringa_xserver_rsa',
            'resource_dir' => '~/hiderin.xyz/payke_resources',
            'public_html_dir' => '~/hiderin.xyz/public_html',

            'user_folder_id' => 'user_007131',
            'user_app_name' => 'tarotaro7',
            'is_first' => '',
            'payke_install_file_path' => '/payke_resources/templates/install.php',
            'payke_env_file_path' => '/payke_resources/templates/.env.php',

            'db_host' => 'localhost',
            'db_username' => 'hirotae_h1de',
            'db_password' => 'matsui1234',
            'db_database' => 'hirotae_payma04',

            'payke_name' => 'payke-ec_v3-22-3',
            'payke_zip_name' => 'payke-ec-cae6ae8bf6d3',
            'payke_zip_file_path' => '/payke_resources/zips/payke-ec-cae6ae8bf6d3.zip',

            'deploy_datetime' => '20231010_123832'
        ];

        $o1 = $ds->exec_deply_unlock($p1);
        // print_r($o1);
        $this->assertEquals($o1[count((array)$o1)-1], 'task deploy:unlock');
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
        $o1 = $ds->create_env_file($n1, $c_ori);
        $this->assertEquals("storage/app/payke_resources/tmp/.env_new.php", $o1);
    }

    /**
     * deploy test.
     */
    public function test_deploy(): void
    {
        $ds = new DeployService();

        $host = new PaykeHost();
        $host->hostname = 'hiderin.xyz';
        $host->remote_user = 'hirotae';
        $host->port = 10022;
        $host->identity_file = './.ssh/hideringa_xserver_rsa';
        $host->resource_dir = '~/hiderin.xyz/payke_resources';
        $host->public_html_dir = '~/hiderin.xyz/public_html';

        $user = new PaykeUser();
        $user->user_folder_id = 'user_007131';
        $user->user_app_name = 'tarotaro7';

        $db = new PaykeDb();
        $db->db_host = 'localhost';
        $db->db_username = 'hirotae_h1de';
        $db->db_password = 'matsui1234';
        $db->db_database = 'hirotae_payma04';

        $payke = new PaykeResource();
        $payke->payke_name = 'ayke-ec_v3-23-1';
        $payke->payke_zip_name = 'payke-ec-6d979f64ed30';
        $payke->payke_zip_file_path = '/storage/app/payke_resources/zips/payke-ec-6d979f64ed30.zip';

        $l1 = [];
        $o1 = $ds->deploy($host, $user, $db, $payke, $l1, true);
        print_r($l1);
        $this->assertTrue($o1);
    }

    /**
     * set_to_affiliate_on test.
     */
    public function test_opan_close_affiliate(): void
    {
        $ds = new DeployService();

        $host = new PaykeHost();
        $host->hostname = 'hiderin.xyz';
        $host->remote_user = 'hirotae';
        $host->port = 10022;
        $host->identity_file = './.ssh/hideringa_xserver_rsa';
        $host->resource_dir = '~/hiderin.xyz/payke_resources';
        $host->public_html_dir = '~/hiderin.xyz/public_html';

        $user = new PaykeUser();
        $user->user_folder_id = 'user_007131';
        $user->user_app_name = 'tarotaro7';

        $l1 = [];
        $o1 = $ds->open_affiliate($host, $user, $l1);
        // print_r($l1);
        $this->assertTrue($o1);
        // $l2 = [];
        // $this->assertTrue($o1);
        // $o2 = $ds->close_affiliate($host, $user, $l2);
        // print_r($o2);
    }
}
