<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 * 主に、Paykeのセキュリティ設定に必要な、ソルト・シードの文字列を作成するHelper
 */
class SecurityHelper
{
    /**
     * セキュリティ関連のハッシュ処理で使われるランダムな文字列を作成する
     */
    public static function create_salt(): string
    {
        // ハッシュ化（暗号化）におけるソルトとは
        // https://cyzennt.co.jp/blog/2021/08/17/%E3%83%8F%E3%83%83%E3%82%B7%E3%83%A5%E5%8C%96%EF%BC%88%E6%9A%97%E5%8F%B7%E5%8C%96%EF%BC%89%E3%81%AB%E3%81%8A%E3%81%91%E3%82%8B%E3%82%BD%E3%83%AB%E3%83%88%E3%81%A8%E3%81%AF/
        $uuid = (string) Str::uuid();
        return sha1($uuid);
    }

    /**
     * 文字列を暗号化／復号化するのに使われるランダムな文字列（数字のみ）を作成する
     */
    public static function create_seed(): string
    {
        return mt_rand().mt_rand().mt_rand();
    }

    /**
     * なんらかの初期値に使用するランダムな文字列を作成する
     */
    public static function create_ramdam_string($length = 8): string
    {
        $uuid = (string) Str::uuid();
        return substr(sha1($uuid), 0, $length);
    }
}