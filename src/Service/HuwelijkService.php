<?php
// src/Service/HuwelijkService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client ;
use GuzzleHttp\RequestOptions;

class HuwelijkService
{
	private $params;
	private $client;
	
	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://api.zaakonline.nl/huwelijken',
				// You can set any number of default request options.
				'timeout'  => 2000.0,
		]);
	}
		
	public function getHuwelijkOnBsn($bsn)
	{
		http://api.zaakonline.nl/huwelijk_bsn
		
		$response =  $this->client->post('/huwelijk_bsn', [
				RequestOptions::JSON => ['bsn' => $bsn]
		]);
		
		return json_decode($response->getBody(),true);
	}
	
}
