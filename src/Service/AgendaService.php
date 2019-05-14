<?php
// src/Service/HuwelijkService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use GuzzleHttp\Client ;
use GuzzleHttp\RequestOptions;

class AgendaService
{
	private $params;
	private $client;
	private $session;
	
	public function __construct(ParameterBagInterface $params, SessionInterface $session)
	{
		$this->params = $params;
		$this->session = $session;
		
		$this->client= new Client([
				// Base URI is used with relative requests
				'base_uri' => 'http://api.zaakonline.nl/huwelijken',
				// You can set any number of default request options.
				'timeout'  => 2000.0,
		]);
	}
		
	public function getHuwelijkOnBsn($bsn)
	{		
		$response =  $this->client->post('/huwelijk_bsn', [
				RequestOptions::JSON => ['bsn' => $bsn]
		]);
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		$this->session->set('user', false);
		
		return $huwelijk;
	}
	
	/* updates the huwelijk to the session */
	public function updateHuwelijk($huwelijk)
	{				
		$this->session->set('huwelijk', $huwelijk);
		$huwelijk= $this->synchronizeHuwelijk();
		
		return $huwelijk;
	}
	
	/* synchronizes the Huwelijk in session with the api */
	/* @todo this could be sped up using async */
	public function synchronizeHuwelijk()
	{	
		$huwelijk = $this->session->get('huwelijk');
		unset($huwelijk['locatie']);
		unset($huwelijk['primairProduct']);
		unset($huwelijk['trouwAmbtenaar']);
		unset($huwelijk['locaties']);
		unset($huwelijk['partners']);
		unset($huwelijk['getuigen']); 
		unset($huwelijk['ambtenaren']);
		unset($huwelijk['documenten']);
		unset($huwelijk['issues']);
		unset($huwelijk['additioneleProducten']);
		//unset($huwelijk['']);
		
		$response =  $this->client->put('/huwelijk/'.$huwelijk['id'], [
				RequestOptions::JSON => $huwelijk
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	
	public function setDate($date, $time)
	{
		$huwelijk = $this->session->get('huwelijk');
		
		$post=[];
		$post['datum'] = $date;
		$post['tijd'] = $time;
		
		$response =  $this->client->put('/huwelijk/'.$huwelijk['id'], [
				RequestOptions::JSON => $post
		]);		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	public function setLocation($id)
	{		
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->post('/huwelijk/'.$huwelijk['id'].'/setLocation', [
				RequestOptions::JSON => ['setLocation' => $id]
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	} 	
	
	public function removeLocation()
	{
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->put('/huwelijk/'.$huwelijk['id'], [
				RequestOptions::JSON => ['location' => null]
		]);
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	} 
	
	public function setProduct($id)
	{
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->post('/huwelijk/'.$huwelijk['id'].'/setProduct', [
				RequestOptions::JSON => ['setProduct' => $id]
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	
	public function removeProduct($id)
	{
		$response =  $this->client->put('/huwelijk/'.$huwelijk['id'], [
				RequestOptions::JSON => ['primairProduct' => null]
		]);
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	} 
	
	public function setOfficial($id)
	{		
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->post('/huwelijk/'.$huwelijk['id'].'/requestOfficial', [
				RequestOptions::JSON => ['setOfficial' => $id]
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	
	public function removeOfficial()
	{
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->put('/huwelijk/'.$huwelijk['id'], [
				RequestOptions::JSON => ['trouwAmbtenaar' => null]
		]);
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	
	
	public function melding()
	{
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->post('/huwelijk/'.$huwelijk['id'].'/melding', [
				RequestOptions::JSON => []
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	
		
	public function aanvraag()
	{
		$huwelijk = $this->session->get('huwelijk');
		
		$response =  $this->client->post('/huwelijk/'.$huwelijk['id'].'/aanvraag', [
				RequestOptions::JSON => []
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
		
	public function invitePartner($partner)
	{		
		$huwelijk = $this->session->get('huwelijk');
				
		$response =  $this->client->post('/huwelijk/'.$huwelijk['id'].'/addPartner', [
				RequestOptions::JSON => $partner
		]);
		
		
		$huwelijk = json_decode($response->getBody(),true);
		
		$this->session->set('huwelijk', $huwelijk);
		
		return $huwelijk;
	}
	
	
	
	
}
