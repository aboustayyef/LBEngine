<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Crawlers\ImageGetter\ImageGetter;
use Aboustayyef\ImageExtractor;

class Post extends Model
{

    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'publishing_date'];

    function source()
    {
    	return $this->belongsTo(Source::class);
    }

    function media()
    {
        return $this->hasOne(Media::class);
    }

    /*
        Finds an image from the post, create a Media object, and associate it with the post
     */
    
    function getImage()
    {
        return (new \App\Crawlers\ImageGetter\ImageGetter)->get($this);
    }

    function getRating()
    {
        return (new \App\ContentProcessors\TextProcessors\RatingGetter($this))->process();
    }

    /*
        This function supplies the details of a post.
        It requires two things to get started: A post url, and a source_id in an
        incomplete Post object
     */
    
    function build()
    {
        return (new \App\Crawlers\PostBuilders\PostBuilder($this, $this->source))->process();
    }

    function channels(){
        return $this->belongsToMany('\App\Models\Channel');
    }
   
    /*
    	Check if a post with url is in the database;
    */
   

    function update_shares(FbClient $fb)
    {
        $this->shares = $fb->get_shares($this->url);
    }

    function update_scores($gravity = 0.90)
    {
        $minutesAgo = $this->publishing_date->diffInMinutes();
        $hoursAgo = $minutesAgo / 60;
        $virality = 12 * log($this->shares); // reduce the variance of shares
        $this->score = $virality * pow($gravity, $hoursAgo)  ;
        $this->score = $this->score > 0 ? $this->score : 0;
        $this->save();
    }

    static function urlExists($url){

    	// make 2 version of url. one with http:// and one with https://
    	// if either exists, return true
    	
    	$a = parse_url($url);
    	array_shift($a);
        $piece = $a['host'] . $a['path'];
        if (isset($a['query'])){
            $piece .= '?'.$a['query'];
        }
        if (isset($a['fragment'])){
            $piece .=  '#'.$a['fragment'];
        }
    	$url1 = 'http://'. $piece;
    	$url2 = 'https://' . $piece;
    	$exists1 = Post::where('url', $url1)->get()->count();
    	$exists2 = Post::where('url', $url2)->get()->count();

    	return ($exists1 + $exists2 > 0);

    }
}
