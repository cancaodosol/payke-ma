<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\ReleaseNote;

class ReleaseNoteService
{
    public function find_all()
    {
        $files = glob('../storage/release_notes/*');

        $results = [];
        foreach ($files as $file) {
            $rows = file($file, FILE_SKIP_EMPTY_LINES);
            $results[] = new ReleaseNote($rows);
        }
        
        return array_reverse($results);
    }
}