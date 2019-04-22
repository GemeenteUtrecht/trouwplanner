<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/reservering")
 */
class ReserveringController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('reservering/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/send")
	 */
	public function sendAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
				
		$this->addFlash('success', 'Uw reservering is verzonden');
		
		return $this->redirect($this->generateUrl('app_extra_index'));
	}
}
