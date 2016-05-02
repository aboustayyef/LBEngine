<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;

class PostsController extends Controller
{
    public function get($order = 'hot')
    {

    	// redirects bad routes
    	if (!in_array($order, ['top','hot', 'latest'])) {
    		return redirect('/posts/hot');
    	}

    	switch ($order) {
    		case 'latest':
    			$posts = \App\Post::with('source')->with('media')->orderBy('publishing_date','desc')->take(20)->get();
    			break;
    		case 'top':
    			return redirect('/posts/top/12h');
    			break;
    		default: //hot
    			$posts = \App\Post::with('source')->with('media')->orderBy('score','desc')->take(20)->get();
    			break;
    	}

   		return view('posts')->with(['posts'=> $posts, 'order' => $order]);

    }

    public function getTop($scope='12h')
    {
        // redirects bad routes
        if (!in_array($scope, ['12h','3d', '1w'])) {
            return redirect('/posts/top/12h');
        }

        $order = 'top';

        switch ($scope) {
            case '3d':
                $timePeriod = (new Carbon)->subDays(3);
	    	break;
	    case '1w':
                $timePeriod = (new Carbon)->subDays(7);
                break;
            default: //12h
                $timePeriod = (new Carbon)->subHours(12);
                break;
        }

        $posts = \App\Post::with('source')->with('media')->Where('publishing_date','>', $timePeriod)->orderBy('shares','desc')->take(20)->get();
        return view('posts')->with(['posts'=> $posts, 'order' => $order, 'scope' => $scope]);

    }
}
