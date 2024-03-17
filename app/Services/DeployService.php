<?php

namespace App\Services;

use App\Models\Deploy;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;
use App\Helpers\SecurityHelper;

class DeployService
{
    private string $payke_ini_file_path___affiliate_on;
    private string $payke_ini_file_path___affiliate_off;
    private string $root_dir;
    private string $resource_dir;
    private string $execute_php_command;

    public function __construct()
    {
        $this->root_dir = dirname(__FILE__)."/../../";
        $this->resource_dir = "storage/app/payke_resources/";
        $this->payke_install_file_path___installed_true = "{$this->resource_dir}templates/install___installed_true.php";
        $this->payke_install_file_path___installed_false = "{$this->resource_dir}templates/install___installed_false.php";
        $this->payke_ini_file_path___affiliate_on = "{$this->resource_dir}templates/paykeec___affiliate_on.ini";
        $this->payke_ini_file_path___affiliate_off = "{$this->resource_dir}templates/paykeec___affiliate_off.ini";
        $this->execute_php_command = env("EXECUTE_PHP_COMMAND", "");
    }

    /** 
     * デプロイ処理をコマンド実行
     **/
    public function exec_deply(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep deploy{$params_string}";
        return $this->exec($command);
    }

    /** 
     * アンロック処理をコマンド実行
     **/
    public function exec_deply_unlock(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep deploy:unlock{$params_string}";
        return $this->exec($command);
    }

    /** 
     * 設定ファイルのセット処理をコマンド実行
     **/
    public function exec_set_ini(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep set_ini{$params_string}";
        return $this->exec($command);
    }

    /** 
     * アプリ名変更の処理をコマンド実行
     **/
    public function exec_rename_app_name(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep create_admin_user{$params_string}";
        return $this->exec($command);
    }

    /** 
     * adminユーザー作成の処理をコマンド実行
     **/
    public function exec_create_admin_user(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep rename_app_name_symlink{$params_string}";
        return $this->exec($command);
    }

    /** 
     * phpからlinuxコマンドを実行する処理
     **/
    public function exec(string $command): array
    {
        $output = [];
        $result_code = -1;
        exec($command, $output, $result_code);
        return $output;
    }

    /**
     * デプロイするデータベース情報をもとに、.env.phpファイルを作成する。
     *
     * @param string $file_name
     * @param array $config
     *
     * @return bool
     */
    public function create_env_file(string $file_name, array $config): string
    {
        $base_path = "{$this->root_dir}{$this->resource_dir}templates/.env.php";
        $to_path = "{$this->root_dir}{$this->resource_dir}tmp/.env_{$file_name}.php";

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

        return $success ? "{$this->resource_dir}tmp/.env_{$file_name}.php" : "";
    }

    /** 
     * Model情報から、デプロイを実行する。
     **/
    public function deploy(PaykeHost $host, PaykeUser $user, PaykeDb $db, PaykeResource $payke, array &$outLog, bool $is_first = false): bool
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($host, $user, $db, $payke);

        $datetime = date("Ymd_His");
        $params['deploy_datetime'] = $datetime;

        // デプロイが初回の場合は、デプロイ先に設定ファイルなどを送る。
        $params['is_first'] = $is_first ? '1' : '';
        $params['payke_install_file_path___installed_true'] = $this->payke_install_file_path___installed_true;
        $params['payke_install_file_path___installed_false'] = $this->payke_install_file_path___installed_false;

        $env = [
            'DB_DATASOURCE' => 'Database/Mysql',
            'DB_HOST' => $db['db_host'],
            'DB_PORT' => 3306,
            'DB_DATABASE' => $db['db_database'],
            'DB_USERNAME' => $db['db_username'],
            'DB_PASSWORD' => $db['db_password'],
            'DB_PREFIX' => '',

            'SECURITY_SALT' => SecurityHelper::create_salt(),
            'SECURITY_CIPHER_SEED' => SecurityHelper::create_seed()
        ];
        $env_file_name = "{$user->user_folder_id}_{$datetime}";
        $params['payke_env_file_path'] = $is_first ? $this->create_env_file($env_file_name, $env) : '';

        $params['payke_ini_file_path'] = $user->enable_affiliate == 1 ? 
            $this->payke_ini_file_path___affiliate_on : $this->payke_ini_file_path___affiliate_off;

        // デプロイを実行す。
        $outLog = $this->exec_deply($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] info successfully deployed!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        $title = "{$payke->version} 更新";
        if($is_success){
            $message = "Payke {$payke->version}のデプロイに成功しました。";
            $logService->write_version_log($user, $title, $message, $payke, $params_string, $outLog);
        }else{
            $message = "Payke {$payke->version}のデプロイに失敗しました。";
            $logService->write_error_log($user, $title, $message, $payke, $params_string, $outLog);
            $o = [];
            $this->unlock($host, $user, $db, $payke, $o);
        }

        if($is_first && $is_success && $user->enable_affiliate == 1)
        {
            $message = "アフィリエイト機能を有効にしました。";
            $logService->write_other_log($user, 'アフィリエイト有効', $message);
        }

        return $is_success;
    }

    /** 
     * Model情報から、アンロックを実行する。
     **/
    public function unlock(PaykeHost $host, PaykeUser $user, PaykeDb $db, PaykeResource $payke, array &$outLog): bool
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($host, $user, $db, $payke);

        // アンロックを実行す。
        $logService = new DeployLogService();
        $outLog = $this->exec_deply_unlock($params);
        $is_success = $outLog[count((array)$outLog)-1] == 'task deploy:unlock';

        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "Deployerをアンロックしました。";
            $logService->write_other_log($user, 'アンロック', $message, null, $params_string, $outLog);
        } else {
            $message = "Deployerのアンロックに失敗しました。";
            $logService->write_other_log($user, 'アンロック', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * アプリ名を変更する。
     */
    public function rename_app_name(PaykeUser $user, string $old_app_name, string $new_app_name, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, new PaykeDb(), new PaykeResource());
        $params['user_app_name'] = $new_app_name;
        $params['user_app_name_old'] = $old_app_name;

        // デプロイを実行す。
        $outLog = $this->exec_rename_app_name($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "公開アプリ名を「{$old_app_name}」から「{$new_app_name}」へ変更しました。";
            $logService->write_other_log($user, "公開アプリ名の変更", $message, null, $params_string, $outLog);
        }else{
            $message = "公開アプリ名の変更に失敗しました。";
            $logService->write_error_log($user, '公開アプリ名の変更失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * Paykeのアフィリエイト機能を有効にする。
     */
    public function open_affiliate(PaykeHost $host, PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($host, $user, new PaykeDb(), new PaykeResource());
        $params['payke_ini_file_path'] = $this->payke_ini_file_path___affiliate_on;

        // デプロイを実行す。
        $outLog = $this->exec_set_ini($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "アフィリエイト機能を有効にしました。";
            $logService->write_other_log($user, 'アフィリエイト有効', $message, null, $params_string, $outLog);
        }else{
            $message = "アフィリエイト機能を有効にするのに、失敗しました。";
            $logService->write_error_log($user, 'アフィリエイト有効失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * Paykeのアフィリエイト機能を無効にする。
     */
    public function close_affiliate(PaykeHost $host, PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($host, $user, new PaykeDb(), new PaykeResource());
        $params['payke_ini_file_path'] = $this->payke_ini_file_path___affiliate_off;

        // デプロイを実行す。
        $outLog = $this->exec_set_ini($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "アフィリエイト機能を無効にしました。";
            $logService->write_other_log($user, 'アフィリエイト無効', $message, null, $params_string, $outLog);
        }else{
            $message = "アフィリエイト機能を無効にするのに、失敗しました。";
            $logService->write_error_log($user, 'アフィリエイト無効失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * 管理ユーザーを作成する
     */
    public function create_admin_user(PaykeUser $user, string $username, string $passpard, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);
        $params['admin_uuid'] = SecurityHelper::create_uuid();
        $params['admin_username'] = SecurityHelper::transfer_safety_bash_text($username);
        $params['admin_password'] = SecurityHelper::transfer_safety_bash_text(SecurityHelper::create_hashed_password($passpard));

        // デプロイを実行す。
        $outLog = $this->exec_rename_app_name($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "PaykeECに管理ユーザー「{$username}」を作成しました。";
            $logService->write_other_log($user, "管理ユーザー作成", $message, null, $params_string, $outLog);
        }else{
            $message = "PaykeECへの管理ユーザー「{$username}」作成に失敗しました。";
            $logService->write_error_log($user, '管理ユーザー作成失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    private function model_to_params(PaykeHost $host, PaykeUser $user, PaykeDb $db, PaykeResource $payke): array
    {
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

        return array_merge($aHost, $aUser, $aDb, $aPayke);
    }

    private function create_params_string(array $params)
    {
        $params_string = '';
        foreach($params as $key => $value)
        {
            $value_string = is_numeric($value) ? (string)$value : '"'.$value.'"';
            $params_string = "{$params_string} -o {$key}={$value_string}";
        }
        return $params_string;
    }
}