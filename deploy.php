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

// パスの起点を決めておく。
set('root_dir', dirname(__FILE__).'/');

// 引数から作成されるディレクトリ情報
set('resource_zips_dir', '{{resource_dir}}/zips');
set('resource_releases_dir', '{{resource_dir}}/{{user_folder_id}}/releases');
set('current_app_path', '{{resource_dir}}/{{user_folder_id}}/current');
set('public_app_path' ,'{{public_html_dir}}/{{user_app_name}}');
set('public_app_path_old' ,'{{public_html_dir}}/{{user_app_name_old}}');

// CakePHPレシピでの設定情報
set('release_name', '{{payke_name}}_{{deploy_datetime}}');
set('deploy_path', '{{resource_dir}}/{{user_folder_id}}');
set('shared_dirs', ['app/tmp/logs']);
set('shared_files', ['app/Config/.env.php', 'app/Config/install.php', 'app/Config/paykeec.ini']);
set('keep_releases', 7);

// Return release path.
// MEMO: シンボリックリンクの存在チェック判定が不安定のため、存在チェックなしで取得するよう変更。
set('release_path', function () {
    $releaseExists = test('[ -L {{deploy_path}}/release ]');
    writeln('');
    writeln(' ------ ');
    writeln('check release_path -> '.$releaseExists);
    writeln(' ------ ');
    writeln(run('cd {{deploy_path}} && pwd && ls -la {{deploy_path}}/release'));
    writeln(' ------ ');
    writeln(run('cd {{deploy_path}}/releases && pwd && ls -la'));
    writeln(' ------ ');
    writeln(run("readlink {{deploy_path}}/release"));
    writeln(' ------ ');
    writeln('');
    $link = trim(run("readlink {{deploy_path}}/release"));
    return substr($link, 0, 1) === '/' ? $link : get('deploy_path') . '/' . $link;
});

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
    writeln('[ BEFORE : deploy:release ] -------');
    writeln(run('cd {{deploy_path}} && pwd && ls -la'));
    writeln('-----------------------------------');
    if(!get('is_first'))
    {
        writeln('データベースのバックアップを取っておくよ。');
        writeln(run('cd {{current_app_path}} && mysqldump --single-transaction -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}} > mysql_{{deploy_datetime}}.dump'));
    }
});

/**
 * Update code
 * 通常版だと、gitリポジトリからアップデートのみだったので、
 * zipファイルを解凍して使用するように、独自で作成した。
 */
task('deploy:update_code', function() {

    writeln('[ BEFORE deploy:update_code ] ------');
    writeln(run('cd {{deploy_path}} && pwd && ls -la'));
    writeln(run('cd {{deploy_path}}/releases && pwd && ls -la'));
    writeln('-----------------------------------');

    // １．デプロイ対象のpayke.zipを存在チェック。なかったら、資材置き場へアップロード
    writeln(run('mkdir -p {{resource_zips_dir}}'));
    if(!exists_payke_zip())
    {
        writeln('Payke Zipをアップロードしていくよ。');
        upload('{{root_dir}}{{payke_zip_file_path}}', '{{resource_zips_dir}}');
    } else {
        writeln('Payke Zipは、あるの使うから大丈夫。');
    }

    // ２．ユーザーごとのディレクトリに、zipファイルを解凍する。
    run('unzip -u {{resource_zips_dir}}/{{payke_zip_name}}.zip -d {{resource_zips_dir}}');
    writeln(run('rm -rf {{resource_releases_dir}}/{{release_name}}'));
    writeln(run('mv {{resource_zips_dir}}/{{payke_zip_name}} {{resource_releases_dir}}/{{release_name}}'));

    // ３．sharedファイルをアップロードする。
    if(get('is_first'))
    {
        writeln('設定ファイルをアップロードしていくよ。');
        writeln(run('mkdir -p {{deploy_path}}/shared/app/Config/'));
        upload('{{root_dir}}{{payke_env_file_path}}', '{{deploy_path}}/shared/app/Config/.env.php');
        upload('{{root_dir}}{{payke_ini_file_path}}', '{{deploy_path}}/shared/app/Config/paykeec.ini');
    }

    // Save revision in REVISION file.
    $rev = '{{ payke_zip_name }}';
    writeln("deploy_path -> {{ deploy_path }}");
    writeln(run("echo ".$rev." > {{ deploy_path }}/release/REVISION"));

    writeln('[ AFTER deploy:update_code ] ------');
    writeln(run('cd {{deploy_path}} && pwd && ls -la'));
    writeln('');
    writeln(' ------ ');
    writeln(run('readlink {{deploy_path}}/release'));
    writeln(' ------ ');
    writeln(test('[ -h {{deploy_path}}/release ]'));
    writeln(' ------ ');
    writeln('');
    writeln(run('cd {{deploy_path}}/releases && pwd && ls -la'));
    writeln('-----------------------------------');
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
    writeln('データマイグレーションを実行。');
    $migration_result = run('cd {{resource_releases_dir}}/{{release_name}} && app/Console/cake-for-Xserver Migrations.migration run all --precheck Migrations.PrecheckCondition');
    writeln($migration_result);

    $is_success = strpos($migration_result, 'All migrations have completed.') !== false;
    if($is_success && get('is_first'))
    {
        writeln('install.phpのinstalledをtrueに更新。');
        // MEMO: installedをtrueの状態で、マイグレーションを行うと、初回マイグレーションの場合エラーが発生する。
        //       なので、install.phpの更新は、マイグレーション実行後に行う。
        upload('{{root_dir}}{{payke_install_file_path}}', '{{deploy_path}}/shared/app/Config/install.php');

        // AmazonPayの集信実行のCRONを設定
        writeln('AmazonPayの集信実行のCRONを設定。');
        run("echo '{{ current_app_path }}/app/Console/cake-for-Xserver recurring_payment process_next_charge' >> {{ resource_dir }}/payke_amazon_pay.sh");

        // アフィリエイトの集計実行のCRONを設定
        writeln('アフィリエイトの集計実行のCRONを設定。');
        run("echo '#{{ current_app_path }}/app/Console/cake-for-Xserver affiliate affiliate_reward' >> {{ resource_dir }}/payke_affiliate.sh");
    }
})->desc('Run migrations');

/**
 * Symlink to App
 * リリースしたアプリソースを、資材置き場からpublic_html配下にシンボリックリンク
 */
after('deploy:symlink','deploy:symlink:public_app');
task('deploy:symlink:public_app', function () {
    writeln(run('unlink {{public_app_path}} || echo unexists.'));
    writeln(run('ln -s {{deploy_path}}/current {{public_app_path}}'));
});

/**
 * Setting Payke Ini
 * アフィリエイト機能の有効/無効を制御するため、設定ファイルを更新する。
 */
task('set_ini', function () {
    upload('{{root_dir}}{{payke_ini_file_path}}', '{{deploy_path}}/shared/app/Config/paykeec.ini');
    writeln('ok!');
});

/**
 * ReSymlink to App
 * アプリ名の変更に伴うシンボリックリンクの張り替えを行う。
 */
task('rename_app_name_symlink', function () {
    writeln(run('unlink {{public_app_path_old}} || echo unexists.'));
    writeln(run('ln -s {{deploy_path}}/current {{public_app_path}}'));
    writeln('ok!');
});