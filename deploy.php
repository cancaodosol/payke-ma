<?php
namespace Deployer;

require 'recipe/cakephp.php';

// // Config

// set('repository', 'https://github.com/cancaodosol/docker-laravel-sail.git');

// add('shared_files', []);
// add('shared_dirs', []);
// add('writable_dirs', []);

// Hosts

host('payke_release')
    ->set('hostname', '{{host_name}}')
    ->set('remote_user', '{{host_remote_user}}')
    ->set('port', '{{host_port}}')
    ->set('identity_file', '{{host_identity_file}}');
    // 初回のSSH接続は、手作業で接続することで対応する。
    // ->set('ssh_arguments', [' -o UserKnownHostsFile=/dev/null', ' -o StrictHostKeyChecking=no']);

// set('user_folder_id', 'user_007131');
// set('user_app_name', 'tarotaro7');
// set('deploy_datetime', '20231009_223332');
// set('payke_name', 'payke-ec_v3-22-3');

// set('payke_zip_name', 'payke-ec-cae6ae8bf6d3');
// set('payke_zip_file_path', '/payke_resources/zips/payke-ec-cae6ae8bf6d3.zip');

// set('is_first', '');
// set('payke_install_file_path' ,'/payke_resources/templates/install.php');
// set('payke_env_file_path' ,'/payke_resources/templates/.env.php');

// set('resource_dir', '~/hiderin.xyz/payke_resources');
// set('public_html_dir', '~/hiderin.xyz/public_html');

// // DB情報
// set('db_host', 'localhost');
// set('db_username', 'hirotae_h1de');
// set('db_password', 'matsui1234');
// set('db_database', 'hirotae_payma04');

// 引数から作成されるディレクトリ情報
set('resource_zips_dir', '{{resource_dir}}/zips');
set('resource_releases_dir', '{{resource_dir}}/{{user_folder_id}}/releases');
set('current_app_path', '{{resource_dir}}/{{user_folder_id}}/current');
set('public_app_path' ,'{{public_html_dir}}/{{user_app_name}}');

// CakePHPレシピでの設定情報
set('release_name', '{{payke_name}}_{{deploy_datetime}}');
set('deploy_path', '{{resource_dir}}/{{user_folder_id}}');
set('shared_dirs', ['app/tmp/logs']);
set('shared_files', ['app/Config/.env.php', 'app/Config/install.php']);
set('keep_releases', 7);

function exists_payke_zip() : bool {
    $line = run('ls -1 {{resource_zips_dir}}');
    $files = explode("\n", $line);
    foreach($files as $file)
    {
        if($file === get('payke_zip_name').'.zip') return true;
    }
    return false;
}

/**
 * Database Backup
 * バージョンの更新の前に、DBのバックアップを取る。
 */
before('deploy:release','deploy:release:db_backup');
task('deploy:release:db_backup', function() {
    if(!get('is_first'))
    {
        writeln('データベースのバックアップを取っておくよ。');
        run('cd {{current_app_path}} && mysqldump --single-transaction -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}} > mysql_{{deploy_datetime}}.dump');
    }
});

/**
 * Update code
 * 通常版だと、gitリポジトリからアップデートのみだったので、
 * zipファイルを解凍して使用するように、独自で作成した。
 */
task('deploy:update_code', function() {
    // １．デプロイ対象のpayke.zipを存在チェック。なかったら、資材置き場へアップロード
    run('mkdir -p {{resource_zips_dir}}');
    if(!exists_payke_zip())
    {
        writeln('Payke Zipをアップロードしていくよ。');
        upload(__DIR__ . '{{payke_zip_file_path}}', '{{resource_zips_dir}}');
    } else {
        writeln('Payke Zipは、あるの使うから大丈夫。');
    }

    // ２．ユーザーごとのディレクトリに、zipファイルを解凍する。
    run('unzip -u {{resource_zips_dir}}/{{payke_zip_name}}.zip -d {{resource_zips_dir}}');
    run('rm -rf {{resource_releases_dir}}/{{release_name}}');
    run('mv {{resource_zips_dir}}/{{payke_zip_name}} {{resource_releases_dir}}/{{release_name}}');

    // ３．sharedファイルをアップロードする。
    if(get('is_first'))
    {
        writeln('設定ファイルをアップロードしていくよ。');
        upload(__DIR__ . '{{payke_env_file_path}}', '{{deploy_path}}/shared/app/Config/.env.php');
        upload(__DIR__ . '{{payke_install_file_path}}', '{{deploy_path}}/shared/app/Config/install.php');
    }
});

/**
 * Create plugins' symlinks
 */
task('deploy:init', function () {
    // Plugin内のwebrootを、App本体のwebrootへシンボリックリンクを貼る処理。
    // しかし、CakePHP3からの実装で、CakePHP2にはないため、この処理は不要。
    // run('{{resource_releases_dir}}/{{release_name}}/app/Console/cake-for-Xserver plugin assets symlink');
})->desc('Initialization');

/**
 * Run migrations
 * cake-for-Xserverを使って、マイグレーションを行うように修正
 */
task('deploy:run_migrations', function () {
    run('cd {{resource_releases_dir}}/{{release_name}} && app/Console/cake-for-Xserver Migrations.migration run all --precheck Migrations.PrecheckCondition');
})->desc('Run migrations');

/**
 * Symlink to App
 * リリースしたアプリソースを、資材置き場からpublic_html配下にシンボリックリンク
 */
after('deploy:symlink','deploy:symlink:public_app');
task('deploy:symlink:public_app', function () {
    run('unlink {{public_app_path}}');
    run('ln -s {{deploy_path}}/current {{public_app_path}}');
});