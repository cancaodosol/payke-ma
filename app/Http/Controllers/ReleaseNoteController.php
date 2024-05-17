<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\ReleaseNoteService;

class ReleaseNoteController extends Controller
{
    public function view_all(Request $request)
    {
        $service = new ReleaseNoteService();
        $notes = $service->find_all();
        return view('release_note.index', ['notes' => $notes]);
    }
}