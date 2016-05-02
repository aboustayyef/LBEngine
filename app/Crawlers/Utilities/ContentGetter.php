<?php 

Namespace App\Crawlers\Utilities;

use GuzzleHttp\Client;

/**
*	an alternative to file_get_contents with error handling 
*/

class ContentGetter
{
	
	private $url ;

	function __construct($url)
	{
		$this->url = urldecode($url);
	}

	public function get()
	{
		$client = new Client();
		$response = $client->request('GET', $this->url);
		// To do: connection error handling
		$body = (string) $response->getBody();
		
		return (string) $body;
	}
}

?>