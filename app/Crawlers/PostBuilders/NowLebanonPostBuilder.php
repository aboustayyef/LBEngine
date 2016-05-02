<?php 

Namespace App\Crawlers\PostBuilders;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;


class NowLebanonPostBuilder extends _DomPostBuilder
{
	
function getTitle(){
		$title = $this->crawler
					  ->filter('#Content_Content_Content_lblArticleTitle')
					  ->first()->text();
		return $title;
	}


	function getDate(){
		$date = $this->crawler
					 ->filter('#Content_Content_Content_lblArticleDate')
					 ->first()->text();
		// remove the string "Published:""
		$date = str_replace('Published: ', '', $date);
		$date = Carbon::createFromFormat('d/m/Y H:i A', $date);
		return $date;
	}


	function getContent(){
		$content = '';
		$paragraphs = $this->crawler->filter('.article_txt p');
		foreach ($paragraphs as $key => $paragraph) {
			$content .= '<p>' . $paragraph->nodeValue . '</p>  ';
		}
		return $content;
	}

}


?>