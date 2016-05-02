<?php 

Namespace App\Crawlers\PostBuilders;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;


class NaharnetPostBuilder extends _DomPostBuilder

{
	
	function getTitle()
		{
			$title = $this->crawler
						  ->filter('h1')
						  ->first()->text();
			return trim($title);
		}

	function getDate()
		{
			$date = $this->crawler
						 ->filter('.byline .timeago')
						 ->first()->attr('title');
			$date = new Carbon($date);
			return $date;
		}


	function getContent()
		{
			$content = '';
			$paragraphs = $this->crawler->filter('p.desc');
			foreach ($paragraphs as $key => $paragraph) 
			{
				$content .= '<p>' . $paragraph->nodeValue . '</p>  ';
			}
			return $content;
		}

}


?>