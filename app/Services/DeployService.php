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

    public function exec(string $command):array
    {
        $output = [];
        $result_code = -1;
        exec($command, $output, $result_code);
        return $output;
    }
}