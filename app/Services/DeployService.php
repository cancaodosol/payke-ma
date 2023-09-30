<?php

namespace App\Services;

use App\Models\Deploy;

class DeployService
{
    public function test():string
    {
        return 'ok!';
    }

    public function exec(string $command):array
    {
        $output = [];
        $result_code = -1;
        exec($command, $output, $result_code);
        return $output;
    }
}