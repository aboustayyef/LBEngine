<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| This is the engine layer of LB apps. 
| It only handles building the database.
| This is why it only has API Endpoints 
|
*/

Route::group(['prefix'=>'api'], function(){
    Route::get('posts/{scope?}/{howmany?}', [
        'uses'  =>  'ApiPostsController@index'
    ]);
});
