<?php

namespace Tests\Unit\Helpers;

use App\Helpers\TimeHelper;
use PHPUnit\Framework\TestCase;

class TimeHelperTest extends TestCase
{
    /**
     * exec test.
     */
    public function test_exec(): void
    {
        $th = new TimeHelper();

        // 秒チェック
        $f0 = strtotime('2020-01-02 03:04:05');
        $t0 = strtotime('2020-01-02 03:04:45');
        $r0 = $th->to_diff_string($f0, $t0);
        $this->assertEquals("さっき", $r0);
    
        // 分チェック
        $f1 = strtotime('2020-01-02 03:15:05');
        $t1 = strtotime('2020-01-02 03:04:05');
        $r1 = $th->to_diff_string($f1, $t1);
        $this->assertEquals("11分前", $r1);

        // 前後チェック
        $f2 = strtotime('2020-01-02 03:04:05');
        $t2 = strtotime('2020-01-02 03:15:05');
        $r2 = $th->to_diff_string($f2, $t2);
        $this->assertEquals("11分後", $r2);

        // 時間チェック
        $f3 = strtotime('2020-01-02 03:15:05');
        $t3 = strtotime('2020-01-01 18:04:05');
        $r3 = $th->to_diff_string($f3, $t3);
        $this->assertEquals("9時間前", $r3);

        // 日チェック
        $f4 = strtotime('2020-01-02 03:15:05');
        $t4 = strtotime('2019-12-20 18:04:05');
        $r4 = $th->to_diff_string($f4, $t4);
        $this->assertEquals("12日前", $r4);

        // １週間チェック
        $f5 = strtotime('2020-01-02 03:15:05');
        $t5 = strtotime('2019-12-10 18:04:05');
        $r5 = $th->to_diff_string($f5, $t5);
        $this->assertEquals("3週間前", $r5);

        // １ヶ月チェック
        $f5 = strtotime('2020-01-02 03:15:05');
        $t5 = strtotime('2019-11-10 18:04:05');
        $r5 = $th->to_diff_string($f5, $t5);
        $this->assertEquals("1ヶ月前", $r5);
    }

    /**
     * exec test.
     */
    public function test_title(): void
    {
        $t1 = '{"uuid":"b40bf1db-d302-4262-867a-2537c677e78b","displayName":"App\\Jobs\\DeployJob","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":180,"retryUntil":null,"data":{"commandName":"App\\Jobs\\DeployJob","command":"O:18:\"App\\Jobs\\DeployJob\":9:{s:13:\"deployService\";O:26:\"App\\Services\\DeployService\":7:{s:62:\"\u0000App\\Services\\DeployService\u0000payke_ini_file_path___affiliate_on\";s:64:\"storage\/app\/payke_resources\/templates\/paykeec___affiliate_on.ini\";s:63:\"\u0000App\\Services\\DeployService\u0000payke_ini_file_path___affiliate_off\";s:65:\"storage\/app\/payke_resources\/templates\/paykeec___affiliate_off.ini\";s:36:\"\u0000App\\Services\\DeployService\u0000root_dir\";s:33:\"\/var\/www\/html\/app\/Services\/..\/..\/\";s:40:\"\u0000App\\Services\\DeployService\u0000resource_dir\";s:28:\"storage\/app\/payke_resources\/\";s:47:\"\u0000App\\Services\\DeployService\u0000execute_php_command\";s:3:\"php\";s:40:\"payke_install_file_path___installed_true\";s:66:\"storage\/app\/payke_resources\/templates\/install___installed_true.php\";s:41:\"payke_install_file_path___installed_false\";s:67:\"storage\/app\/payke_resources\/templates\/install___installed_false.php\";}s:16:\"paykeUserService\";O:29:\"App\\Services\\PaykeUserService\":0:{}s:4:\"host\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:20:\"App\\Models\\PaykeHost\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:4:\"user\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:20:\"App\\Models\\PaykeUser\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:9:\"PaykeHost\";i:1;s:7:\"PaykeDb\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:2:\"db\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:18:\"App\\Models\\PaykeDb\";s:2:\"id\";i:3;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:5:\"payke\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:24:\"App\\Models\\PaykeResource\";s:2:\"id\";i:20;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:8:\"is_first\";b:0;s:5:\"title\";s:68:\"「 岡本太郎 」のPaykeを「 v3.25.13 」にアップデート\";s:5:\"delay\";O:13:\"Carbon\\Carbon\":4:{s:4:\"date\";s:26:\"2024-02-07 20:17:45.243349\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:10:\"Asia\/Tokyo\";s:18:\"dumpDateProperties\";a:2:{s:4:\"date\";s:26:\"2024-02-07 20:17:45.243349\";s:8:\"timezone\";s:91:\"O:21:\"Carbon\\CarbonTimeZone\":2:{s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:10:\"Asia\/Tokyo\";}\";}}}"}}';
        $j1 = json_decode($t1);
        print($j1);
    }
}