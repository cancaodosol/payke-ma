<?php

return array(
    // アプリ名
    'APP_NAME' => 'Payke',

    // Debugレベル
    'DEBUG' => 0,

    // セキュリティ関連のハッシュ処理で使われるランダムな文字列
    'SECURITY_SALT' => '097490cdd602fe103b531321b3f52ebd917fb5a9',

    // 文字列を暗号化／復号化するのに使われるランダムな文字列（数字のみ）
    'SECURITY_CIPHER_SEED' => '7610992454678815261222026164',

    // データベース接続情報
    'DB_DATASOURCE' => 'Database/Mysql',
    'DB_HOST' => 'localhost',
    'DB_PORT' => 3306,
    'DB_DATABASE' => 'hirotae_payma04',
    'DB_USERNAME' => 'hirotae_h1de',
    'DB_PASSWORD' => 'matsui1234',
    'DB_PREFIX' => '',

    'TEST_DB_DATASOURCE' => 'Database/Mysql',
    'TEST_DB_HOST' => 'localhost',
    'TEST_DB_PORT' => 3306,
    'TEST_DB_DATABASE' => 'test_database_name',
    'TEST_DB_USERNAME' => 'user',
    'TEST_DB_PASSWORD' => 'password',
    'TEST_DB_PREFIX' => '',

	// 'TUNNEL_ENDPOINT_URL' => 'https://******.ngrok.io',
);
