<?php 

/*
 *
 *	Global functions for the App
 *
 */



// alternative to file_get_contents with error handling

function url_get_contents($url){
	return (new \App\Crawlers\Utilities\ContentGetter($url))->get();
}


// Content Scrubber
// Takes a big pile of html and returns a "cleaner" version

function scrub_content($html)
{
	$junkTags= [
		"style", "form", "iframe", "script", "button", "input", "textarea", 
        "noscript", "select", "option", "object", "applet", "basefont",
        "bgsound", "blink", "canvas", "command", "menu", "nav", "datalist",
        "embed", "frame", "frameset", "keygen", "label", "marquee", "link"
    ];

    // remove the junk tags
    foreach ($junkTags as $tag) {
    	$html = preg_replace("#<\\s*$tag\\s*>.*<\\s*/\\s*$tag\\s*>#um", " ", $html);
    }

    // remove empty tags
    $html = preg_replace("#<\\s*\\w+\\s*>\\s*<\\s*/\\s*\\w+\\s*>#um", " ", $html);

    // remove extra white spaces
    $html = preg_replace("#\\s+#um", " ", $html);

    return $html;
}

// Text Summarizer

function summarize_content($html, $result_sentences = 3)
{
	$summarizer = new \Aboustayyef\Summarizer;
	$summarizer->text = $html;
	return $summarizer->summarize($result_sentences);
}

// resolve urls to final destination

function final_url($url)
{
        $resolver = new \Resolver\URLResolver();
        $resolver->isDebugMode(true);
        $resolver->setMaxRedirects(10);
        $resolver->setUserAgent('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
        $resolver->isDebugMode(false);
        return $resolver->resolveURL($url)->getURL();
}
?>