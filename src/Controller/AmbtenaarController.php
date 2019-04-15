<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\AmbtenaarService;
/**
 * @Route("/ambtenaren")
 */
class AmbtenaarController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, AmbtenaarService $ambtenaarService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$ambtenaren= $ambtenaarService->getAll();
				
		return $this->render('ambtenaar/index.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'ambtenaren' => $ambtenaren,
		]);
	}
	
	/**
	 * @Route("/voor-een-dag")
	 */
	public function voorEenDagAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('ambtenaar/voor-een-dag.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
		]);
	}
	
	/**
	 * @Route("/zelfstandig")
	 */
	public function zelfstandigAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('ambtenaar/zelfstandig.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
		]);
	}
	
	/**
	 * @Route("/{id}")
	 */
	public function viewAction(Session $session, $id, AmbtenaarService $ambtenaarService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$ambtenaar = $ambtenaarService->getOne($id);
		
		return $this->render('ambtenaar/ambtenaar.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'ambtenaar' => $ambtenaar,
		]);
	}
}
