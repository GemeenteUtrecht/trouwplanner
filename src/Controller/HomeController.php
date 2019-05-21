<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\HuwelijkService;
use App\Service\BRPService;

class HomeController extends AbstractController
{ 	
	
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		//var_dump($huwelijk);
		
		return $this->render('home/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/login")
	 */
	public function loginAction(Session $session, Request $request, HuwelijkService $huwelijkService, BRPService $brpService)
	{
		if($persoon = $brpService->getPersonOnBsn($request->request->get('bsn'))){
			$session->set('user', $person);
			$this->addFlash('success', 'Welkom '.$persoon['voornamen']);			
		}
		else{
			$this->addFlash('danger', 'U kon helaas niet worden ingelogd');		
		}
				
		$response = $this->forward('App\Controller\HuwelijkController::indexAction');		
		return $response;
	}
	
	/**
	 * @Route("/logout")
	 */
	public function logoutAction(Session $session)
	{
		$session->set('huwelijk', false);
		$session->set('user', false);
		
		$response = $this->forward('App\Controller\HomeController::indexAction');
		return $response;
	}
	
	/**
	 * @Route("/data")
	 */
	public function dataAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$response = new JsonResponse($huwelijk);
		return $response;
	}
}
