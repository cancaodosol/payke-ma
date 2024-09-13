<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use App\Helpers\HtmlHelper;

class ReleaseNote
{
    public function __construct($rows)
    {
        $headers = ["## 主題", "## 背景", "## リリース内容"];
        $values = [];
        preg_match('/# .+ \(/', $rows[0], $matches);
        $values["version"] = str_replace(["# ", " ("], "", $matches[0]);
        preg_match('/\(.+\)/', $rows[0], $matches);
        $values["created_at"] = str_replace(["(", ")"], "", $matches[0]);
        $header_name = "";
        foreach ($rows as $row) {
            if(in_array(trim($row), $headers)){
                $header_name = trim(str_replace("#", "", $row));
                $values[$header_name] = "";
                continue;
            }
            if($header_name != "") {
                $values[$header_name] = $values[$header_name].$row;
            }
        }

        $this->version = trim($values["version"]);
        $this->title = trim($values["主題"]);
        $this->background = trim($values["背景"]);
        $this->content = trim($values["リリース内容"]);
        $this->created_at = trim($values["created_at"]);
    }

    public function background_by_md() : string
    {
        return HtmlHelper::markdown_to_html($this->background);
    }

    public function content_by_md() : string
    {
        return HtmlHelper::markdown_to_html($this->content);
    }

    public function getDiffTime() : string
    {
        $th = new TimeHelper();
        $now = time();
        return $th->to_diff_string($now, strtotime($this->created_at));
    }
}
