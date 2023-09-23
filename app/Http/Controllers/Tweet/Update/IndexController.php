<?php

namespace App\Http\Controllers\Tweet\Update;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use App\Services\TweetService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TweetService $tweetService)
    {
        $tweetId = (int) $request->route('tweetId');
        if(!$tweetService->isOwnTweet($request->user()->id, $tweetId))
        {
            throw new AccessDeniedHttpException();
        }
        $tweet = Tweet::where('id', $tweetId)->firstOrFail();
        return view('tweet.update')->with('tweet', $tweet);
    }
}
