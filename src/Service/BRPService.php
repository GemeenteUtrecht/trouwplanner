<?php
// src/Service/BRPService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;

class BRPService
{
	private $params;
	private $client;
	
	public function __construct(ParameterBagInterface $params, SessionInterface $session)
	{
		$this->params = $params;
		$this->session = $session;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://brp.demo.zaakonline.nl/',
				// You can set any number of default request options.
				'timeout'  => 2000.0,
		]);
	}
	
	public function getPersonOnBsn($bsn)
	{		
		$response = $this->client->request('GET','/personen',['query' => ['burgerservicenummer' => $bsn]]);
		$response = json_decode($response->getBody(), true);
		return $response["hydra:member"][0];
	}
	
}
