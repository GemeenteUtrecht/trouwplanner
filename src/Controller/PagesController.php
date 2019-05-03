<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\ProductService;
/**
 * @Route("/paginas")
 */
class PagesController extends AbstractController
{ 
	/**
	* @Route("/melding")
	*/
	public function meldingAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('pages/melding.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/naamsgebruik")
	 */
	public function naamsgebruikAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('pages/naamsgebruik.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	/**
	 * @Route("/getuigen")
	 */
	public function getuigenAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('pages/getuigen-kiezen.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
}
