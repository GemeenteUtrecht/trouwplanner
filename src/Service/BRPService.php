<?php
// src/Service/BRPService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;

class BRPService
{
	private $params;
	private $client;
	
	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://api.zaakonline.nl/',
				// You can set any number of default request options.
				'timeout'  => 2000.0,
		]);
	}	
	
	public function getPersonOnBsn($bsn)
	{
		
	}
	
}
