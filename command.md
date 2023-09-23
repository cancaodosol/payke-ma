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