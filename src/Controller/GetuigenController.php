<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\HuwelijkService;

/**
 * @Route("/getuigen")
 */
class GetuigenController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('getuigen/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	} 
	
	/**
	 * @Route("/invite")
	 */
	public function inviteAction(Session $session, HuwelijkService $huwelijkService)
	{
		$this->addFlash('success', 'Uw getuigen is uitgenodigd');
		
		return $this->redirect($this->generateUrl('app_locatie_index'));
	}
}
