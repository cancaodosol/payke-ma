<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;

class GitServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $git_dir = dirname(__FILE__)."/../../../";
        $since = date("Y-m-d", strtotime('-7 day'));
        $gitlogs = $this->get_gitlog_since($git_dir, $since);
        
        foreach ($gitlogs as $log) {
            print(date("Y/m/d H:i .  ", strtotime($log->author->date." +9 hour")));
            print($log->author->date." ".$log->author->name." ".$log->subject." \n");
        }
    }

    public function get_gitlog_since($git_dir, $since){
        $git_cmd = <<<EOD
        git log --since=$since --pretty=format:'{%n  "commit": "%H",%n  "abbreviated_commit": "%h",%n  "tree": "%T",%n  "abbreviated_tree": "%t",%n  "parent": "%P",%n  "abbreviated_parent": "%p",%n  "refs": "%D",%n  "encoding": "%e",%n  "subject": "%s",%n  "sanitized_subject_line": "%f",%n  "body": "%b",%n  "commit_notes": "%N",%n  "verification_flag": "%G?",%n  "signer": "%GS",%n  "signer_key": "%GK",%n  "author": {%n    "name": "%aN",%n    "email": "%aE",%n    "date": "%aD"%n  } %n},'
        EOD;
        $cmd = "cd $git_dir && $git_cmd";
        $result = $this->exec($cmd);
        $json_string = "[".substr(implode("", $result), 0, -1)."]";
        return json_decode($json_string);
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
}
