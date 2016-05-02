<?php 

namespace App\Crawlers;

use App\Models\Source;
use App\Models\Post;

/**
* 
* This class acts as a router for classes that fetch lists of posts
* if Source has an RSS feed, use RssPostsCrawler
* otherwise use DomPostsCrawler
* 
*/

class PostsCrawler
{
	private $source;

	function __construct(Source $source)
	{
		$this->source = $source;
	}

	public function get()
	{
		$className = $this->source->rss_feed? "App\Crawlers\RssPostsCrawler" : "App\Crawlers\DomPostsCrawler";
		return (new $className($this->source))->get();
	}
}

?>