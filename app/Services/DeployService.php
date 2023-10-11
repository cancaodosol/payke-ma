<?php

namespace App\Services;

use App\Models\Deploy;

class DeployService
{
    public string $payke_install_file_path = '/payke_resources/templates/install.php';

    public function test():string
    {
        return 'ok!';
    }

    public function exec_deply(array $params): array
    {
        $params_string = '';
        foreach($params as $key => $value)
        {
            $value_string = is_numeric($value) ? (string)$value : '"'.$value.'"';
            $params_string = "{$params_string} -o {$key}={$value_string}";
        }
        $command = "php vendor/bin/dep deploy{$params_string}";
        return $this->exec($command);
    }

    public function exec_deply_unlock(array $params): array
    {
        $params_string = '';
        foreach($params as $key => $value)
        {
            $value_string = is_numeric($value) ? (string)$value : '"'.$value.'"';
            $params_string = "{$params_string} -o {$key}={$value_string}";
        }
        $command = "php vendor/bin/dep deploy:unlock{$params_string}";
        return $this->exec($command);
    }

    public function exec(string $command): array
    {
        $output = [];
        $result_code = -1;
        exec($command, $output, $result_code);
        return $output;
    }
    /**
     * Create environment file by app/payke_resources/.env.php.
     *
     * @param string $file_name
     * @param array $config
     *
     * @return bool
     */
    public function create_env_file(string $file_name, array $config): string
    {
        $base_path = "{__DIR__}/../payke_resources/templates/.env.php";
        $to_path = "{__DIR__}/../payke_resources/tmp/.env_{$file_name}.php";

        $environment = [];
        foreach ($config as $k => $v) {
            $name = $k;
            $environment[$name] = $v;
        }

        $contents = file_get_contents($base_path);
        $contents = preg_replace_callback(
            '/[\'\"]([^\'\"]*)[\'\"]\s*=>\s*[\'\"]?([^\'\"]*)[\'\"]?\s*,/',
            function (array $matches) use ($environment) {
                $name = $matches[1];
                if (isset($environment[$name])) {
                    $value = $environment[$name];
                    return var_export($name, true).' => '.var_export($value, true).',';
                }
                return $matches[0];
            },
            $contents
        );

        $success = file_put_contents($to_path, $contents);

        return $success ? "/payke_resources/tmp/.env_{$file_name}.php" : "";
    }

    public function deploy(array $host, array $user, array $db, array $payke, bool $is_first = false)
    {
        $datetime = date("Ymd_His");

        // デプロイが初回の場合は、デプロイ先に設定ファイルなどを送る。
        $user['is_first'] = $is_first ? '1' : '';
        $user['payke_install_file_path'] = $this->payke_install_file_path;

        $env = [
            'DB_DATASOURCE' => 'Database/Mysql',
            'DB_HOST' => $db['db_host'],
            'DB_PORT' => 3306,
            'DB_DATABASE' => $db['db_database'],
            'DB_USERNAME' => $db['db_username'],
            'DB_PASSWORD' => $db['db_password'],
            'DB_PREFIX' => ''
        ];
        $env_file_name = $user['user_id'].'_'.$datetime;
        $user['payke_env_file_path'] = $is_first ? $this->create_env_file($env_file_name, $env) : '';

        $params = array_merge($host, $user, $db, $payke); 
        $params['deploy_datetime'] = $datetime;

        $result = $this->exec_deply($params);
        return $result;
    }
}