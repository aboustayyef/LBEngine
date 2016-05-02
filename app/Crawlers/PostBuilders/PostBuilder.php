<?php 

Namespace App\Crawlers\PostBuilders;

use Embed\Embed;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Post;
use App\Models\Source;

/**
*
* 	This class fills out the missing information in a Post object
*  	It usually used for Dom-crawled articles.
*  	it fixes unusable information (like relative links)
*   and crawls, analyzes and scrubbs missing information.
* 
* 	@param $post \App\Post
*   @param $source \App\Source
*/

class PostBuilder
{

	private $post;
	private $source;
	
	private $url;
	private $crawler;
	
	function __construct(Post $post, Source $source)
	{
		$this->post = $post;
		$this->source = $source;
		$this->url = $this->get_absolute_permalink();
	}

	public function process()
	{
		// ignore requests from sources with Rss feeds
		// because they already contain complete information

		if ($this->source->rss_feed) {

			// do some cleanup and sanitization for Rss posts contents

			$this->post->content = scrub_content($this->post->content);
			$this->post->excerpt = summarize_content($this->post->content);
			return $this->post;
		}

		// otherwise, start by getting content and building crawler

		$content = url_get_contents($this->url);
		$crawler = new Crawler();
		$crawler->addHtmlContent($content);

		// select class based on source
		
		$lookupTable = [
			'now.mmedia.me'		=>	'App\Crawlers\PostBuilders\NowLebanonPostBuilder',
			'www.dailystar.com.lb'	=>	'App\Crawlers\PostBuilders\DailyStarPostBuilder',
			'www.naharnet.com'	=>	'App\Crawlers\PostBuilders\NaharnetPostBuilder',
			'thenational.ae'	=>	'App\Crawlers\PostBuilders\TheNationalPostBuilder',
		];

		$className = $lookupTable[parse_url($this->source->homepage)['host']];

		$details = (new $className($this->url, $crawler))->getDetails();
		$this->post->url = $details['url'];
		$this->post->title = $details['title'];
		$this->post->publishing_date = $details['publishing_date'];
		$this->post->content = $details['content'];
		$this->post->excerpt = $details['excerpt'];
		$this->post->source_id = $this->source->id;

		return $this->post;
	}

	public function get_absolute_permalink()
	{
		if ($this->post->url[0] == '/') {
			$urlParts = parse_url($this->source->homepage);
			$root = $urlParts['scheme'] . '://' . $urlParts['host'] ;
			$this->post->url = $root . $this->post->url;
		}

		return $this->post->url;
	}
}

?>