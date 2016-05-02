<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model

{

	protected $fillable = [
	    'shorthand', 'description', 'color', 'parent_id'
	];

    public static function getFromShorthand($shorthand)
    {
        $channel = Channel::where('shorthand', $shorthand)->get()->first();
        return $channel;
    }
   
    public function __toString()
    {
    	return $this->shorthand;
    }
    
    public function children()
    {
    	return $this->hasMany('App\Models\Channel', 'parent_id');
    }

    public function addChild(Channel $channel)
    {
    	$this->children()->save($channel);
    }

    public function addChildren($children)
    {
    	$this->children()->saveMany($children);
    }

    public function removeChild(Channel $child)
    {
    	if ($this->id == $child->parent_id) {
    		$child->parent_id = null;
    		$child->save();
    	}
    }


    public function parent()
    {     
        return $this->belongsTo('App\Models\Channel');
    }


    public function sources()
    {
        return $this->belongsToMany('App\Models\Source');
    }
}
