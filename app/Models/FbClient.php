<?php 

namespace App\Models;

use Facebook\Facebook;

/**
* Facebook client to get graph data
*/
class FbClient
{
	private $fb;

	function __construct()
	{
		$this->fb = new Facebook
		([
      		'app_id' => getenv('FACEBOOK_KEY'),
      		'app_secret' => getenv('FACEBOOK_SECRET'),
      		'default_graph_version' => 'v2.5',
	    ]);
	}

	function get_url_details($url)
	{
	    $response = $this->fb->get('/?id=' .urlencode($url) , getenv('FACEBOOK_TOKEN'));
	    return $response->getDecodedBody();
	}

	function get_shares($url)
	{
		$details = $this->get_url_details($url);
        if (isset($details['share'])){
            $shares = $details['share']['share_count'];
            return $shares;
        }		
        return 0;
	}
}

?>
