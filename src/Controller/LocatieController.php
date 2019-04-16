<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\LocatieService;
/**
 * @Route("/locaties")
 */
class LocatieController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, LocatieService $locatieService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$locaties = $locatieService->getAll();
		
		return $this->render('locatie/index.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'locaties' => $locaties,
		]);
	}
	
	/**
	 * @Route("/{id}")
	 */
	public function viewAction(Session $session, $id, LocatieService $locatieService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		
		$locatie= $locatieService->getOne($id);
		
		return $this->render('locatie/locatie.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'locatie' => $locatie,
		]);
	}
}
