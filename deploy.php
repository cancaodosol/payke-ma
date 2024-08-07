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
set('shared_files', ['app/Config/ma.ini', 'app/Config/.env.php', 'app/Config/paykeec.ini']);
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

// Return the release path during a deployment
// but fallback to the current path otherwise.
// MEMO: シンボリックリンクの存在チェック判定が不安定のため、存在チェックなしで取得するよう変更。
set('release_or_current_path', function () {
    $releaseExists = trim(run("readlink {{deploy_path}}/release"));
    $result = $releaseExists ? get('release_path') : get('current_path');
    writeln('release_path ? : '.$releaseExists);
    writeln('release_or_current_path : '.$result);
    return $result;
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

    // １．デプロイ対象のpayke.zipを存在チェック。なかったら、資材置き場へアップロード
    writeln(run('mkdir -p {{resource_zips_dir}}'));
    if(!exists_payke_zip())
    {
        writeln('Payke Zipをアップロードしていくよ。');
        writeln('▼ {{resource_zips_dir}}/{{payke_zip_file_path}}');
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
        writeln('▼ {{deploy_path}}/shared/app/Config/.env.php');
        upload('{{root_dir}}{{payke_env_file_path}}', '{{deploy_path}}/shared/app/Config/.env.php');
        writeln('▼ {{deploy_path}}/shared/app/Config/paykeec.ini');
        upload('{{root_dir}}{{payke_ini_file_path}}', '{{deploy_path}}/shared/app/Config/paykeec.ini');
    }

    // PaykeMAとの連携情報を渡す。
    writeln('▼ {{deploy_path}}/shared/app/Config/ma.ini');
    upload('{{root_dir}}{{payke_ma_file_path}}', '{{deploy_path}}/shared/app/Config/ma.ini');

    // Save revision in REVISION file.
    $rev = '{{ payke_zip_name }}';
    writeln("deploy_path -> {{ deploy_path }}");
    writeln(run("echo ".$rev." > {{ deploy_path }}/release/REVISION"));

    writeln('[ AFTER deploy:update_code ] ------');
    writeln(run('cd {{deploy_path}} && pwd && ls -la'));
    writeln('');
    writeln(' ------ ');
    writeln('test -h : ' . test('[ -h {{deploy_path}}/release ]'));
    writeln(' ------ ');
    writeln('test -L : ' . test('[ -L {{deploy_path}}/release ]'));
    writeln(' ------ ');
    writeln(run('readlink {{deploy_path}}/release'));
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
        // AmazonPayの集信実行のCRONを設定
        writeln('AmazonPayの集信実行のCRONを設定。');
        run("echo '{{ current_app_path }}/app/Console/cake-for-Xserver recurring_payment process_next_charge' >> {{ resource_dir }}/payke_amazon_pay.sh");

        // アフィリエイトの集計実行のCRONを設定
        writeln('アフィリエイトの集計実行のCRONを設定。');
        run("echo '#{{ current_app_path }}/app/Console/cake-for-Xserver affiliate affiliate_reward' >> {{ resource_dir }}/payke_affiliate.sh");
    }
    writeln('install.phpのinstalledをtrueに更新。');
    // MEMO: installedをtrueの状態でマイグレーションを行うと、エラーが発生することがあるので、
    //       毎度、ここでtrueにし直す。(デプロイのスタートは、zipのpaykeなので、初期値はfalse。)
    writeln('▼ {{release_path}}/app/Config/install.php');
    upload('{{root_dir}}{{payke_install_file_path___installed_true}}', '{{release_path}}/app/Config/install.php');
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
 * Open Affiate
 * アフィリエイト機能の有効にするため、設定ファイルを更新する。
 */
task('open_affiliate', function () {
    run('echo membrino=1 > {{deploy_path}}/shared/app/Config/paykeec.ini');
    writeln(run('cat {{deploy_path}}/shared/app/Config/paykeec.ini'));
    writeln('ok!');
});

/**
 * Close Affiate
 * アフィリエイト機能の無効にするため、設定ファイルを更新する。
 */
task('close_affiliate', function () {
    run('echo membrino=0 > {{deploy_path}}/shared/app/Config/paykeec.ini');
    writeln(run('cat {{deploy_path}}/shared/app/Config/paykeec.ini'));
    writeln('ok!');
});

/**
 * Setting Payke Message Ini
 * メッセージ表示用の設定ファイルを更新する。
 */
task('put_ma_file', function () {
    writeln('▼ {{deploy_path}}/shared/app/Config/.ma.php');
    upload('{{root_dir}}{{payke_ma_file_path}}', '{{deploy_path}}/shared/app/Config/.ma.php');
    writeln(run('cat {{deploy_path}}/shared/app/Config/.ma.php'));
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

/**
 * Create Admin User
 * 管理ユーザーの追加を行う。
 */
task('create_admin_user', function () {
    $create_admin_sql = <<<EOT
    'INSERT INTO users (id, username, email, password, role, created) VALUES ("{{admin_uuid}}", "{{admin_username}}", "{{admin_email}}", "{{admin_password}}", "admin", NOW())'
    EOT;
    writeln($create_admin_sql);
    writeln(run("mysql -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}} -e{$create_admin_sql}"));
    writeln('ok!');
});

/**
 * Remake Ini User
 * 初回作成されるadminユーザーを置き換えて、メンテナンス用のユーザーにする。
 */
task('replace_admin_to_superadmin', function () {
    $update_superadmin_sql = <<<EOT
    'UPDATE users SET username = "{{superadmin_username}}", password = "{{superadmin_password}}", modified = NOW() WHERE username = "admin"'
    EOT;
    writeln($update_superadmin_sql);
    writeln(run("mysql -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}} -e{$update_superadmin_sql}"));
    writeln('ok!');
});

/**
 * Lock Users
 * すべてのユーザーのログインをできないようにする（メンテナンス用ユーザーを除く）
 */
task('lock_users', function () {
    $lock_users_sql = <<<EOT
    'UPDATE users SET locked =  NOW() WHERE username <> "{{superadmin_username}}"'
    EOT;
    writeln($lock_users_sql);
    writeln(run("mysql -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}} -e{$lock_users_sql}"));
    writeln('ok!');
});

/**
 * Unlock Users
 * すべてのユーザーのログイン制限を解除する
 */
task('unlock_users', function () {
    $unlock_users_sql = <<<EOT
    'UPDATE users SET locked =  NULL'
    EOT;
    writeln($unlock_users_sql);
    writeln(run("mysql -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}} -e{$unlock_users_sql}"));
    writeln('ok!');
});

/**
 * Stop App
 * アプリを使用できないようにする
 */
task('stop_app', function () {
    writeln(run("cd {{deploy_path}} && mkdir -p stopped"));
    writeln('▼ {{deploy_path}}/stopped/.htaccess');
    upload('{{root_dir}}{{htaccess_for_stop_path}}', '{{deploy_path}}/stopped/.htaccess');
    writeln(run('unlink {{public_app_path}} || echo unexists.'));
    writeln(run('ln -s {{deploy_path}}/stopped {{public_app_path}}'));
    writeln(run('ls -l {{public_html_dir}}'));
    writeln('ok!');
});

/**
 * Restart App
 * アプリを使用できるようにする
 */
task('restart_app', function () {
    writeln(run('unlink {{public_app_path}} || echo unexists.'));
    writeln(run('ln -s {{deploy_path}}/current {{public_app_path}}'));
    writeln(run('ls -l {{public_html_dir}}'));
    writeln('ok!');
});

/**
 * Check Database Connection
 * データベースの接続確認。
 */
task('check_db_connection', function () {
    writeln(run("mysql -h {{db_host}} -u {{db_username}} -p{{db_password}} {{db_database}}"));
    writeln('ok!');
});
