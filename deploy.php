<?php
namespace Deployer;

require 'recipe/common.php';

// Config

set('repository', 'https://github.com/cancaodosol/docker-laravel-sail.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('hiderin.xyz')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/html');

// Hooks

after('deploy:failed', 'deploy:unlock');
