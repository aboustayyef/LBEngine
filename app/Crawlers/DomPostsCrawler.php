<?php 

namespace App\Crawlers;

use App\Models\Source;
use App\Models\Post;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Collection;

/**
* 	Fetches lists of posts using source-specific selectors
* 	returns a collection of \App\Post objects with missing details 
* 	(only post urls and source id is supplied)
*/

class DomPostsCrawler
{
	private $source;
	private $lookupTable; 

	function __construct(Source $source)
	{
		$this->source = $source;

		/*
			This Lookup table associates each homepage with the selectors used to fetch
			a list of post links. The selectors can be found using chrome's dev tools:
			Right-click, select one of the links to posts. select "copy selector";
			use jQuery in the console to test that the selector is unique. Simplify it.
		 */
		
		$this->lookupTable = 
		[
			'now.mmedia.me'			=>	'#Content_Content_Content_PagedListControl_dvListing a',
			'www.naharnet.com'	=>	'#content .title.ugly-link',
			'www.dailystar.com.lb'	=>	['.divCaptionAlone a','.ic_caption a'],
			'thenational.ae' => '.holder h4 > a',
		];
	}

	public function get()
	{

		// get html content from homepage using guzzle
		$url = $this->source->homepage;
		$body = url_get_contents($url);

		// create Symfony crawler object from retrieved html
		$crawler = new Crawler();
		$crawler->addHtmlContent($body);

		// choose source's specific selector using lookup table
		$host = $this->getHost($url);
		$selector = $this->lookupTable[$host]; 
		// to do: error handle

		// collect links into a collection object then return it
		$links = new Collection();

		if (is_array($selector)) 
		{
			$allLinks = new \SplObjectStorage;
			foreach ($selector as $key => $s) 
			{
				$allLinks->addAll($crawler->filter($s));
			} 
		} else {
			$allLinks = $crawler->filter($selector);
		}
		
		foreach ($allLinks as $key => $link) {
			$post = new Post;
			$post->url = $this->get_absolute_permalink($link->getAttribute('href'));
			$post->source_id = $this->source->id;
			$links->push($post);
		}

		return $links;

	}

	private function getHost($url)
	{
		// returns beirutspring.com from https://beirutspring.com/some/path/some-file.html
		return parse_url($url)['host'];
		
	}

	public function get_absolute_permalink($url)
	{
		if ($url[0] == '/') {
			$urlParts = parse_url($this->source->homepage);
			$root = $urlParts['scheme'] . '://' . $urlParts['host'] ;
			$url = $root . $url;
		}

		return $url;
	}
}

?>


