<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/datum")
 */
class DatumController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('datum/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/set")
	 */
	public function setAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$this->addFlash('success', 'Datum voorkeur opgeslagen');
		
		return $this->redirect($this->generateUrl('app_reservering_index'));
	}
}
