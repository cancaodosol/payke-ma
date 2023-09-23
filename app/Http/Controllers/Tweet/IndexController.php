<?php

namespace App\Http\Controllers\Tweet;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use App\Services\TweetService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TweetService $tweetService)
    {
        // $tweets = Tweet::all()->sortByDesc('created_at');
        $tweets = $tweetService->getTweets();
        return view('tweet.index', ['tweets' => $tweets]);
    }
}
