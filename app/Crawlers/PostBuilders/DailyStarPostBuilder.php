<?php 

Namespace App\Crawlers\PostBuilders;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;


class DailyStarPostBuilder extends _DomPostBuilder

{
	
function getTitle(){
		$title = $this->crawler
					  ->filter('#bodyHolder_divTitle')
					  ->first()->text();
		return $title;
	}


	function getDate(){
		$date = $this->crawler
					 ->filter('#bodyHolder_divDate')
					 ->first()->text();
		// clean up between the parenthesis
		$date = trim(preg_replace('#\(.*\)#','',$date));

		// create from lebanese timezone then convert to UTC
		$date = Carbon::createFromFormat('M\. d\, Y \| g:i A', $date, 'Asia/Beirut')->setTimeZone('UTC');
		return $date;
	}


	function getContent(){
		$content = '<p>' . $this->crawler->filter('meta[name="Description"]')->attr('content') . '<p>';
		return $content;
	}

}


?>
