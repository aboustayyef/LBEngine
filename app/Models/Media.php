<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Storage;
use \Image;

class Media extends Model
{
	function __construct($url=null)
	{
		Parent::__construct();

		if (null != $url) 
		{
			$dimensions = getimagesize($url);

			$this->original_location = $url;
			$this->original_width = $dimensions[0];
			$this->original_height = $dimensions[1];
			$this->hash = md5($url);
		}
	}

    public function post()
    {
    	return $this->belongsTo('\App\Post');
    }

    public function source()
    {
    	return $this->belongsTo('\App\Source');
    }

    public function createCache()
    {
    	if ($this->cached == 0) {

    		$i = Image::make($this->original_location);

    		// resize
    		// $i = $i->resize(300, null, function ($constraint) {
    		//     $constraint->aspectRatio();
    		// });
    		
            // fit
            $i = $i->fit(300,200);
    		$result = Storage::disk('public')->put('img/' . $this->hash . '.jpg', $i->encode('jpg')->stream()->__toString());
    		if ($result) {
    			$this->cached = 1;
    			$this->save();
    		}
    		return $result;
    	}
    	return false;
    }

    public function deleteCache()
    {
        if ($this->cached == 1) {
           $result = Storage::disk('public')->delete('img/' . $this->hash . '.jpg');
           if ($result) {
            $this->cached = 0;
            $this->save();
           } 
        }
    }

}
