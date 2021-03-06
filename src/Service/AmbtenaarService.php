<?php
// src/Service/AmbtenaarService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client ;

use App\Service\CommonGroundService;


class AmbtenaarService
{
	private $params;
	private $client;
	private $commonGroundService;
	
	public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
	{
		$this->params = $params;
		$this->commonGroundService = $commonGroundService;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://ambtenaren.demo.zaakonline.nl/ambtenaren',
				// You can set any number of default request options.
				'timeout'  => 4000.0,
		]);
	}
		
	public function getAll()
	{
		$response = $this->client->request('GET');
		$response = json_decode($response->getBody(), true);
		$responses = $response["hydra:member"];
		
		// Lets get the persons for ambtenaren
		foreach($responses as $key=>$value){
			$responses[$key]["persoon"] = $this->commonGroundService->getSingle($value["persoon"]);			
		}
		return $responses;
	}
	
	public function getOne($id)
	{
		$response = $this->client->request('GET','ambtenaren/'.$id);
		$response = json_decode($response->getBody(), true);
		$response['persoon'] = $this->commonGroundService->getSingle($response["persoon"]);
		return $response;
	}
	
	public function save($ambtenaar)
	{
		if($ambtenaar['id']){
			$response = $this->client->put('ambtenaar/'.$ambtenaar['id'], [
					\GuzzleHttp\RequestOptions::JSON => $ambtenaar
			]);
		}
		else{
			$response = $this->client->post('ambtenaren', [
					\GuzzleHttp\RequestOptions::JSON => $ambtenaar
			]);
		}
		
		//$response=  $this->client->send($request);
		$response = json_decode($response->getBody(), true);
		return $response;
	}	
}
