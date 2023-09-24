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
    run('whoami');
});

// // Hooks

// after('deploy:failed', 'deploy:unlock');
