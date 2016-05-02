<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{

    protected $guarded = [];

    public function channels()
    {
    	return $this->belongsToMany('\App\Channel');
    }

    public function assignChannel(Channel $channel)
    {
    	$this->channels()->save($channel);
    }

    public function posts()
    {
    	return $this->hasMany(Post::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function hasMedia($url)
    {
        return $this->media()->lists('original_location')->contains($url);
    }

    public function addPost(Post $post)
    {
    	$this->posts()->save($post);
    }

    public function crawlLatestPosts()
    {
        // crawls the web for the latest posts by this source;
        // returns an array of urls;
        $postsCrawler = new Crawlers\PostsCrawler($this);
        return $postsCrawler->get();
    }
}
