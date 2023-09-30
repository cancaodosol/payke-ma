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

set('release_or_current_path', function () {
    return 'hiderin.xyz/public_html';
});

set('payke_zip_name', function () {
    return 'payke-ec-752d7ee2ff92.zip';
});

set('payke_zip_file_path', function () {
    return '/payke_zips/{{payke_zip_name}}';
});

task('upload', function () {
    upload(__DIR__ . '{{payke_zip_file_path}}', '{{release_or_current_path}}');
});
    

// // Hooks

// after('deploy:failed', 'deploy:unlock');
