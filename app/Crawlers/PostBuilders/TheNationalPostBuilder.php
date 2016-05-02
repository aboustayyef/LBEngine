<?php 

Namespace App\Crawlers\PostBuilders;

use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class TheNationalPostBuilder extends _DomPostBuilder
{
    function getTitle(){
        $title = $this->crawler
                    ->filter('h1[itemprop="name"]')
                    ->first()->text();
        return $title;
    }

    function getDate(){
        $date = $this->crawler
                    ->filter('div.articleinfo > p:nth-child(2) > span')
                    ->first()->text();
        // remove the string "Updated:""
        $date = str_replace('Updated: ', '', $date);
        $date = Carbon::createFromFormat('F j, Y h:i A', $date);
        return $date;
    }

    function getContent(){
        $content = '';
        $paragraphs = $this->crawler->filter('.article-body-page p');
        foreach ($paragraphs as $key => $paragraph) {
            $content .= '<p>' . $paragraph->nodeValue . '</p>  ';
        }
        return $content;
    }
}
?>
