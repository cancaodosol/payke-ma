# コマンドなどのメモ書き

## つぶやき編集画面作成

sail artisan make:controller Tweet/Update/IndexController --invokable
sail artisan make:controller Tweet/Update/PutController --invokable
sail artisan make:request Tweet/UpdateRequest

## つぶやき削除画面作成

sail artisan make:controller Tweet/DeleteController --invokable

## ログイン機能を追加する

sail composer require laravel/breeze
sail artisan breeze:install
sail npm install
sail npm run dev

## ミドルウェアを追加する

sail artisan make:middleware SampleMiddleware

## ログイン機能をつぶやきアプリと連動する

sail artisan make:migration add_user_id_to_tweets
sail artisan make:seeder UserSeeder
sail artisan migrate:fresh --seed

## Deployerを試していくよ

sail artisan make:test Services/DeployServiceTest --unit
sail test tests/Unit/Services/DeployServiceTest.php

sail composer require --dev deployer/deployer
sail php vendor/bin/dep init

## Deployerを実行する

### my_taskというタスクを実行する
sail php vendor/bin/dep my_task

### 引数を渡す
sail php vendor/bin/dep my_task -v -o current_date="I don't know"

## Sail環境に独自のカスタマイズを入れる
sail artisan sail:publish

sail down
sail build
sail up -d

## デプロイ情報登録

sail artisan make:model PaykeHost -mfsc
sail artisan make:model PaykeDb -mfsc
sail artisan make:model PaykeResource -mfsc
sail artisan migrate:refresh --seed

insert into payke_dbs values (null, 0, 1, 'localhost', 'hirotae_h1de', 'matsui1234', 'hirotae_payma03', '2023-10-14 14:13:24', '2023-10-14 14:13:24');

## マイグレーション

$table->renameColumn('id', 'id_stnk');
$table->integer('name')->nullable()->change(); //Null許容するように変更
$table->integer('name')->change(); //Null許容しないに変更
$table->integer('version_x')->after('version');
$table->dropColumn('user_id');

update payke_users set status = 1, user_name = 'PAYKE TARO' where id = 1;