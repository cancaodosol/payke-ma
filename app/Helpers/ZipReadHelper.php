<?php

namespace App\Helpers;

use ZipArchive;

class ZipReadHelper
{
    // 参考記事：https://symfoware.blog.fc2.com/blog-entry-1891.html
    // 公式Doc：https://www.php.net/manual/ja/class.ziparchive.php
    public function get_payke_version($targetZipPath)
    {
        $config_file_name = '/app/Config/constants.php';

        $version = 'v-1.-1.-1';
        $zip = new ZipArchive();
        if(!$zip->open($targetZipPath))
        {
            return $version;
        }

        $filename = '';
        for($i = 0; $i < $zip->numFiles; $i++)
        {
            if(str_ends_with($zip->getNameIndex($i), $config_file_name))
            {
                $filename = $zip->getNameIndex($i);
                break;
            }
        }

        // ファイルポインタを取得
        $fp = $zip->getStream($filename);
        while (!feof($fp)) {
            // 文字コードを変換
            $line = mb_convert_encoding(fgets($fp), 'UTF-8', 'CP932');

            // "$config['app.version'] = 'v3.2y.zz'; の行を探す。
            if(strpos($line, 'app.version'))
            {
                $p = explode(" ", $line);
                $version = substr($p[count($p) - 1], 1, strlen($p[count($p) - 1]) - 4);
                break;
            }
        }
        return $version;
    }
}