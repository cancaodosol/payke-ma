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
}