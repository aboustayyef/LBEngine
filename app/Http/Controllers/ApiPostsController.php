<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ApiPostsController extends Controller
{
    public function index($scope = 'latest', $howmany=20){
        if (!in_array($scope, ['latest','top','hot'])){
            abort(404);        
        }
        switch ($scope) {
            case 'latest':
    			$posts = \App\Models\Post::with('source')->with('media')->orderBy('publishing_date','desc')->take($howmany)->get();
                return $posts;
                break;
            case 'top';
                $posts = \App\Models\Post::with('source')->with('media')->orderBy('score','desc')->take($howmany)->get();
                return $posts;
                break;
            case 'hot';
                return 'hot';
                break;
        }
    }
}
