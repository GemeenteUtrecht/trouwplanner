<?php
// src/Service/ProductService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;

class ProductService
{
	private $params;
	private $client;
	
	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://api.zaakonline.nl/producten',
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
		$response = $this->client->request('GET','/product/'.$id);
		$response = json_decode($response->getBody(), true);
		return $response;
	}
	
	
	public function save($product)
	{
		if($product['id']){
			$response = $this->client->put('product/'.$product['id'], [
					\GuzzleHttp\RequestOptions::JSON => $product
			]);
		}
		else{
			$response = $this->client->post('products', [
					\GuzzleHttp\RequestOptions::JSON => $product
			]);
		}
		//$response=  $this->client->send($request);
		$response = json_decode($response->getBody(), true);
		return $response;
	}
}
