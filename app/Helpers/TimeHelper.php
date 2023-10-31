<?php

namespace App\Helpers;

class TimeHelper
{
    public function to_diff_string($from, $to)
    {
        $diff_sec = $from - $to;

        $before_or_after = $diff_sec < 0 ? "後" : "前";
        if($diff_sec < 0) $diff_sec = $diff_sec * -1;

        if($diff_sec < 60) return "さっき";

        $diff_min = (int)($diff_sec / 60);
        if($diff_min < 60) return "{$diff_min}分{$before_or_after}";

        $diff_hours = (int)($diff_min / 60);
        if($diff_hours <= 24) return "{$diff_hours}時間{$before_or_after}";

        $diff_days = (int)($diff_hours / 24);
        if($diff_days <= 20) return "{$diff_days}日{$before_or_after}";

        $diff_weeks = (int)($diff_days / 7);
        if($diff_weeks <= 3) return "{$diff_weeks}週間{$before_or_after}";

        $diff_months = (int)($diff_weeks / 4);
        return "{$diff_months}ヶ月{$before_or_after}";
    }
}