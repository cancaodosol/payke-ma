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