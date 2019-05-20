<?php
// src/Service/LocatieService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;

class LocatieService
{
	private $params;
	private $client;
	
	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://api.zaakonline.nl/locaties',
				// You can set any number of default request options.
				'timeout'  => 2000.0,
		]);
	}
		
	public function getAll()
	{		
		$response = $this->client->request('GET');
		$response = json_decode($response->getBody(), true);		
		return $response["hydra:member"];
	}
	
	public function getOne($id)
	{
		$response = $this->client->request('GET','/locaties/'.$id);
		$response = json_decode($response->getBody(), true);
		return $response;
	}
	
	public function save($locatie)
	{
		if($locatie['id']){
			$response = $this->client->put('locaties/'.$locatie['id'], [
					\GuzzleHttp\RequestOptions::JSON => $locatie
			]);
		}
		else{
			$locatie= $this->client->post('locaties', [
					\GuzzleHttp\RequestOptions::JSON => $locatie
			]);
		}
		//$response=  $this->client->send($request);
		$response = json_decode($response->getBody(), true);
		return $response;
	}
	
}
