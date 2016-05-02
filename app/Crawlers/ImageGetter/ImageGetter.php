<?php 

Namespace App\Crawlers\ImageGetter;

use App\Models\Media;
use App\Models\Post;
use Aboustayyef\ImageExtractor;

class ImageGetter
{
	function get( Post $post)
	{
		# crawls post url for images
		echo "getting images for post: [$post->url]" . PHP_EOL;
		$imageExtractor = new ImageExtractor($post->url, $post->content, true);
		
		# if post already has media, delete it
		if ($post->media()->count() > 0) {
		    $post->media()->delete();
		}

		# keep looking for images until a unique one is found or no images returned
		$uniqueOrNoImageFound = false;
		$counter = 0;
		$disqualified = [];

		while (!$uniqueOrNoImageFound) 
		{
		    
		    $image_url = $imageExtractor->get(299, true);
		    
		    # if no images found, exit
		    if (null == $image_url) 
		    {
		        $uniqueOrNoImageFound = true;
		        continue;
		    }

		    # if result image already exists and is associated with the source, ignore
		    
		    if ($post->source->hasMedia($image_url)) 
		    { 
		    	// if for some reason the image has been disqualified before
		    	// and has still shown up, abort
		    	
		    	if (in_array($image_url, $disqualified)) {
		    		echo "The image [$image_url] has been disqualified before. Aborting" . PHP_EOL; 
		    		$uniqueOrNoImageFound = true;
		    		$image_url = null;
		    		continue;
		    	}

		    	echo "[$image_url]" . PHP_EOL;
		        echo "image exists, disqualifying" . PHP_EOL ;
		        $imageExtractor->disqualify($image_url);
		        $disqualified[] = $image_url;
		        continue;
		    } 
		    $uniqueOrNoImageFound = true;          

		}

		if (null == $image_url) {
		    return 'Could not find good image for this post';
		}

		$media = new Media($image_url);
		$media->post_id = $post->id;
		$media->source_id = $post->source->id;

		$media->save();
		return $media;
	}
}


?>