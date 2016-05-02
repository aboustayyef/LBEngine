<?php 

namespace App\Crawlers;

use Illuminate\Support\Collection;
use App\Models\Source;
use App\Models\Post;
use \Feeds;
use \Carbon\Carbon;

/**
*	The class fetches a collection of App\Post objects from the Rss feed of a source
*	Unlike DomPostsCrawler, this class gets complete posts that do not require to be built 
*/

class RssPostsCrawler
{
	private $source;

	function __construct(Source $source)
	{
		$this->source = $source;
	}

	public function get()
	{
		$feed = Feeds::make($this->source->rss_feed);
		$items = $feed->get_items(0, 5);
		$posts = new Collection;

		foreach ($items as $key => $item) {
			$post = new Post;
			$post->url = $item->get_permalink();
			$post->title = $item->get_title();

			echo "processing post: [$post->title]" . PHP_EOL;

			$post->publishing_date = new Carbon($item->get_date());

			$post->content = $item->get_content();
			$post->source_id = $this->source->id;
			$posts->push($post);
			// todo: Get content and excerpt through content filters.
		}

		return $posts;
		// get details using SimplePie
	}
}

?>