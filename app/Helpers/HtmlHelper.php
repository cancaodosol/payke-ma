<?php

namespace App\Helpers;

class HtmlHelper
{
    public function __construct()
    {
    }

    // 参考記事：https://shkn.hatenablog.com/entry/2019/05/06/025657
    static public function markdown_to_html($markdownText)
    {
        $Parsedown = new \Parsedown();
        $Parsedown->setSafeMode(true);
        return $Parsedown->text($markdownText);
    }
}