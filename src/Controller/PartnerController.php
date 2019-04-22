<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/partner")
 */
class PartnerController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('partner/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	
	/**
	 * @Route("/invite")
	 */
	public function inviteAction(Session $session)
	{		
		$this->addFlash('success', 'Uw partner is uitgenodigd');		
		
		return $this->redirect($this->generateUrl('app_locatie_index'));
	}
	
}
