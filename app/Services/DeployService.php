<?php

namespace App\Services;

use App\Models\Deploy;
use App\Models\DeployLog;
use App\Models\PaykeDb;
use App\Models\PaykeHost;
use App\Models\PaykeResource;
use App\Models\PaykeUser;
use App\Helpers\SecurityHelper;

use Exception;

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
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep rename_app_name_symlink{$params_string}";
        return $this->exec($command);
    }

    /** 
     * adminユーザー作成の処理をコマンド実行
     **/
    public function exec_create_admin_user(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep create_admin_user{$params_string}";
        return $this->exec($command);
    }

    /** 
     * ログイン制御処理のコマンド実行
     **/
    public function exec_lock_users(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep lock_users{$params_string}";
        return $this->exec($command);
    }

    /** 
     * ログイン制御の解除処理のコマンド実行
     **/
    public function exec_unlock_users(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep unlock_users{$params_string}";
        return $this->exec($command);
    }

    /** 
     * アプリ停止処理のコマンド実行
     **/
    public function exec_stop_app(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep stop_app{$params_string}";
        return $this->exec($command);
    }

    /** 
     * アプリ再開処理のコマンド実行
     **/
    public function exec_restart_app(array $params): array
    {
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep restart_app{$params_string}";
        return $this->exec($command);
    }

    /** 
     * deployerのコマンド実行
     **/
    public function exec_deployer_command(string $command, array $params): array
    {
        if(!in_array($command, [
            "replace_admin_to_superadmin",
            "put_ma_file"
        ])){
            throw new \Exception('Not Found Deployer Command ['.$command.'].');
        }
        $params_string = $this->create_params_string($params);
        $command = "cd {$this->root_dir} && {$this->execute_php_command} vendor/bin/dep {$command}{$params_string}";
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
     * @param PaykeUser $user
     * @param PaykeDb $db
     *
     * @return bool
     */
    public function create_env_file(PaykeUser $user, PaykeDb $db): string
    {
        $config = [
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

        $base_path = "{$this->root_dir}{$this->resource_dir}templates/.env.php";
        $to_path = "{$this->root_dir}{$this->resource_dir}tmp/.env_{$user->user_folder_id}.php";

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

        return $success ? "{$this->resource_dir}tmp/.env_{$user->user_folder_id}.php" : "";
    }

    /**
     * ユーザー情報をもとに、.ma.phpファイルを作成する。
     *
     * @param PaykeUser $user
     *
     * @return bool
     */
    public function create_ma_file(PaykeUser $user): string
    {
        $base_path = "{$this->root_dir}{$this->resource_dir}templates/.ma.php";
        $to_path = "{$this->root_dir}{$this->resource_dir}tmp/.ma_{$user->user_folder_id}.php";

        $contents = file_get_contents($base_path);
        $contents = str_replace('NOTICE_FOR_ALL_URL_VALUE', route("home")."/notice/all.php", $contents);
        $contents = str_replace('NOTICE_FOR_PERSONAL_ADMIN_URL_VALUE', route("home")."/notice/personal/{$user->user_folder_id}_admin.php", $contents);
        $contents = str_replace('NOTICE_FOR_PERSONAL_LOGIN_URL_VALUE', route("home")."/notice/personal/{$user->user_folder_id}_login.php", $contents);

        $success = file_put_contents($to_path, $contents);

        return $success ? "{$this->resource_dir}tmp/.ma_{$user->user_folder_id}.php" : "";
    }

    /**
     * アプリ使用停止用の .htaccessファイルを作成する。
     */
    public function create_htaccess_for_stop(): string
    {
        $base_path = "{$this->root_dir}{$this->resource_dir}templates/.htaccess_for_stop";
        $to_path = "{$this->root_dir}{$this->resource_dir}tmp/.htaccess_for_stop";

        $contents = file_get_contents($base_path);
        $contents = str_replace("APP_IS_STOPPED_URL", route('app.stopped'), $contents);

        $success = file_put_contents($to_path, $contents);

        return $success ? "{$this->resource_dir}tmp/.htaccess_for_stop" : "";
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

        $params['payke_env_file_path'] = $is_first ? $this->create_env_file($user, $db) : '';
        $params['payke_ma_file_path'] = $is_first ? $this->create_ma_file($user) : '';

        $params['payke_ini_file_path'] = $user->enable_affiliate == 1 ? 
            $this->payke_ini_file_path___affiliate_on : $this->payke_ini_file_path___affiliate_off;

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);

        try
        {
            // デプロイを実行す。
            $outLog = $this->exec_deply($params);
            $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] info successfully deployed!';
    
            $title = "{$payke->version} 更新";
            if($is_success){
                $message = "Payke {$payke->version}のデプロイに成功しました。";
                $logService->write_version_log($user, $title, $message, $payke, $params_string, $outLog);
            }else{
                $message = "Payke {$payke->version}のデプロイに失敗しました。";
                $logService->write_error_log($user, $title, $message, $payke, $params_string, $outLog);
                $o = [];
                $this->unlock($host, $user, $db, $payke, $o);
                return $is_success;
            }
    
            if($is_first && $user->enable_affiliate == 1)
            {
                $message = "アフィリエイト機能を有効にしました。";
                $logService->write_other_log($user, 'アフィリエイト有効', $message);
            }
    
            return $is_success;    
        return $is_success;
            return $is_success;    
        }
        catch(Exception $e)
        {
            $title = "想定外のエラー";
            $message = "デプロイ中に、想定外のエラーが発生しました。";
            $logService->write_error_log($user, $title, $message, $payke, $params_string, [$e->getMessage()]);
            return false;
        }
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
        $outLog = $this->exec_create_admin_user($params);
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

    /**
     * 初回作成されるadminユーザーを置き換えて、メンテナンス用のユーザーにする
     */
    public function replace_admin_to_superadmin(PaykeUser $user, string $username, string $passpard, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);
        $params['superadmin_username'] = $username;
        $params['superadmin_password'] = SecurityHelper::transfer_safety_bash_text(SecurityHelper::create_hashed_password($passpard));

        // デプロイを実行す。
        $outLog = $this->exec_deployer_command("replace_admin_to_superadmin", $params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "PaykeECのメンテナンス用ユーザーを作成しました。";
            $logService->write_other_log($user, "メンテナンス用ユーザー作成", $message, null, $params_string, $outLog);
        }else{
            $message = "PaykeECのメンテナンス用ユーザーの作成に失敗しました。";
            $logService->write_error_log($user, 'メンテナンス用ユーザー作成失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * すべてのユーザーのログインをできないようにする（メンテナンス用ユーザーを除く）
     */
    public function lock_users(PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);
        $params['superadmin_username'] = "admin";

        // デプロイを実行す。
        $outLog = $this->exec_lock_users($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "PaykeECにログイン制限をかけました。";
            $logService->write_warm_log($user, "ログイン制限", $message, null, $params_string, $outLog);
        }else{
            $message = "PaykeECにログイン制限をかけることに失敗しました。";
            $logService->write_error_log($user, 'ログイン制限失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * すべてのユーザーのログイン制限を解除する
     */
    public function unlock_users(PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);

        // デプロイを実行す。
        $outLog = $this->exec_unlock_users($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "PaykeECにログイン制限を解除しました。";
            $logService->write_success_log($user, "ログイン制限解除", $message, null, $params_string, $outLog);
        }else{
            $message = "PaykeECにログイン制限の解除に失敗しました。";
            $logService->write_error_log($user, 'ログイン制限解除失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * アプリを停止する
     */
    public function stop_app(PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);

        // アプリ停止用の.htaccessファイルを作成する。
        $htaccess_for_stop_path = $this->create_htaccess_for_stop();
        $params['htaccess_for_stop_path'] = $htaccess_for_stop_path;

        // デプロイを実行す。
        $outLog = $this->exec_stop_app($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "PaykeECを停止しました。";
            $logService->write_warm_log($user, "アプリ停止", $message, null, $params_string, $outLog);
        }else{
            $message = "PaykeECの停止に失敗しました。";
            $logService->write_error_log($user, 'アプリ停止失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * アプリを再開する
     */
    public function restart_app(PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);

        // デプロイを実行す。
        $outLog = $this->exec_restart_app($params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "PaykeECを再開しました。キャッシュをクリアしてから、PaykeECにアクセスしてください。";
            $logService->write_success_log($user, "アプリ再開", $message, null, $params_string, $outLog);
        }else{
            $message = "PaykeECの再開に失敗しました。";
            $logService->write_error_log($user, 'アプリ再開失敗', $message, null, $params_string, $outLog);
        }

        return $is_success;
    }

    /**
     * maファイルを設置する。
     */
    public function put_ma_file(PaykeUser $user, array &$outLog)
    {
        // Modelでもらった情報を、配列に詰め直す。
        $params = $this->model_to_params($user->PaykeHost, $user, $user->PaykeDb, $user->PaykeResource);

        $params['payke_ma_file_path'] = $this->create_ma_file($user);

        // デプロイを実行す。
        $outLog = $this->exec_deployer_command("put_ma_file", $params);
        $is_success = $outLog[count((array)$outLog)-1] == '[payke_release] ok!';

        $logService = new DeployLogService();
        $params_string = $this->create_params_string($params);
        if($is_success){
            $message = "maファイルを設置しました。";
            $logService->write_other_log($user, "maファイル設置", $message, null, $params_string, $outLog);
        }else{
            $message = "maファイルを設置に失敗しました。";
            $logService->write_error_log($user, 'maファイル設置失敗', $message, null, $params_string, $outLog);
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