<?php 

Namespace App\Crawlers\PostBuilders;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;


abstract class _DomPostBuilder
{

	// common initialization logic
	protected $url, $crawler;

	public function __construct($url, $crawler){
		$this->url = $url;
		$this->crawler = $crawler;
	}

	// common functions

	public function getDetails(){

		$content = scrub_content($this->getContent());
		$excerpt = summarize_content($content);

		return [
					'url'				=> $this->url,
					'title'				=> $this->getTitle(),
					'publishing_date'	=> $this->getDate(),
					'content'			=> $content,
					'excerpt'			=> $excerpt
				];
	}

	// functions that differ between media sources
	
	abstract function getTitle();
	abstract function getDate();
	abstract function getContent();

}


?>