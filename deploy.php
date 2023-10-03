<?php
namespace Deployer;

require 'recipe/common.php';

// // Config

// set('repository', 'https://github.com/cancaodosol/docker-laravel-sail.git');

// add('shared_files', []);
// add('shared_dirs', []);
// add('writable_dirs', []);

// Hosts

host('payke_release_test')
    ->set('hostname', 'hiderin.xyz')
    ->set('remote_user', 'hirotae')
    ->set('port', 10022)
    ->set('identity_file', './.ssh/hideringa_xserver_rsa');

task('my_task', function () {
    writeln('The {{alias}} is {{hostname}}');
    writeln('File list: {{dir_list}}');
    // writeln('What time is it? {{current_date}}');
});

set('release_or_current_path', 'hiderin.xyz/public_html');

set('payke_zip_name', 'payke-ec-752d7ee2ff92');

set('payke_app_name', 'payke04');

set('payke_zip_file_path', function () {
    return '/payke_resources/{{payke_zip_name}}.zip';
});

set('payke_install_file_path', function () {
    return '/payke_resources/install.php';
});

set('payke_env_file_path', function () {
    return '/payke_resources/.env.php';
});

set('release_app_root', function () {
    return 'hiderin.xyz/public_html/{{payke_app_name}}';
});

task('upload', function () {
    upload(__DIR__ . '{{payke_zip_file_path}}', '{{release_or_current_path}}');
});

task('unzip', function () {
    run('ls {{release_or_current_path}} -al');
    run('unzip -u {{release_or_current_path}}/{{payke_zip_name}}.zip -d {{release_or_current_path}}');
    run('mv {{release_or_current_path}}/{{payke_zip_name}} {{release_or_current_path}}/{{payke_app_name}}');
});

task('upload_payke_config', function () {
    upload(__DIR__ . '{{payke_env_file_path}}', '{{release_app_root}}/app/Config');
    upload(__DIR__ . '{{payke_install_file_path}}', '{{release_app_root}}/app/Config');
    // run('{{release_app_root}}/app/Console/cake-for-Xserver plugin load Migrations');
    // run('{{release_app_root}}/app/Console/cake-for-Xserver migrations migration run all');
});

// // Hooks

// after('deploy:failed', 'deploy:unlock');
