<?php

namespace App\Services;

use App\Models\Deploy;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;

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
        $command = "cd ../ && php vendor/bin/dep deploy{$params_string}";
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
        $command = "cd ../ && php vendor/bin/dep deploy:unlock{$params_string}";
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
        $base_path = dirname(__FILE__)."/../../payke_resources/templates/.env.php";
        $to_path = dirname(__FILE__)."/../../payke_resources/tmp/.env_{$file_name}.php";

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

    public function deploy(PaykeHost $host, PaykeUser $user, PaykeDb $db, PaykeResource $payke, array &$outLog, bool $is_first = false): bool
    {
        // Modelでもらった情報を、配列に詰め直す。
        $datetime = date("Ymd_His");

        $aHost = [
            'hostname' => $host->hostname,
            'remote_user' => $host->remote_user,
            'port' => $host->port,
            'identity_file' => $host->identity_file,
            'resource_dir' => $host->resource_dir,
            'public_html_dir' => $host->public_html_dir
        ];

        $aUser = [
            'user_folder_id' => $user->user_folder_id,
            'user_app_name' => $user->user_app_name
        ];

        $aDb = [
            'db_host' => $db->db_host,
            'db_username' => $db->db_username,
            'db_password' => $db->db_password,
            'db_database' => $db->db_database
        ];

        $aPayke = [
            'payke_name' => $payke->payke_name,
            'payke_zip_name' => $payke->payke_zip_name,
            'payke_zip_file_path' => $payke->payke_zip_file_path
        ];

        // デプロイが初回の場合は、デプロイ先に設定ファイルなどを送る。
        $aUser['is_first'] = $is_first ? '1' : '';
        $aUser['payke_install_file_path'] = $this->payke_install_file_path;

        $env = [
            'DB_DATASOURCE' => 'Database/Mysql',
            'DB_HOST' => $db['db_host'],
            'DB_PORT' => 3306,
            'DB_DATABASE' => $db['db_database'],
            'DB_USERNAME' => $db['db_username'],
            'DB_PASSWORD' => $db['db_password'],
            'DB_PREFIX' => ''
        ];

        $env_file_name = $aUser['user_folder_id'].'_'.$datetime;
        $aUser['payke_env_file_path'] = $is_first ? $this->create_env_file($env_file_name, $env) : '';

        // ここまでに作成したものを、１つの配列にまとめる。
        $params = array_merge($aHost, $aUser, $aDb, $aPayke); 
        $params['deploy_datetime'] = $datetime;

        $unlock = $this->exec_deply_unlock($params);
        // dd($unlock);
        $outLog = $this->exec_deply($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] info successfully deployed!';
        return $is_success;
    }
}