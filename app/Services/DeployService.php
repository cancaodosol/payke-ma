<?php

namespace App\Services;

use App\Models\Deploy;

class DeployService
{
    public function test():string
    {
        return 'ok!';
    }

    public function exec_deply(array $params):array
    {
        $params_string = '';
        foreach($params as $key => $value)
        {
            $value_string = '"'.$value.'"';
            $params_string = "{$params_string} -o {$key}={$value_string}";
        }
        $command = "php vendor/bin/dep deploy_payke{$params_string}";
        return $this->exec($command);
    }

    public function exec(string $command):bool | array
    {
        $output = [];
        $result_code = -1;
        exec($command, $output, $result_code);
        $is_success = $result_code == 0;
        return $is_success ? $output : $is_success;
    }
    /**
     * Create environment file by app/payke_resources/.env.php.
     *
     * @param string $file_name
     * @param array $config
     *
     * @return bool
     */
    public function create_env_file(string $file_name, array $config)
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
                    print(var_export($name, true).' => '.var_export($value, true).',');
                    return var_export($name, true).' => '.var_export($value, true).',';
                }

                return $matches[0];
            },
            $contents
        );

        $success = file_put_contents($to_path, $contents);

        return $success ? $to_path : $success;
    }
}