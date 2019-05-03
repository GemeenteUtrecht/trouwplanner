<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\LocatieService;
use App\Service\HuwelijkService;

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
				
		// What if we already have an official?
		if($huwelijk['locatie'] && $huwelijk['locatie']['id']){
			return $this->redirect($this->generateUrl('app_locatie_view', ['id'=> (int)$huwelijk['locatie']['locatie']['id']]));
		}	
		
		$locaties = $locatieService->getAll();
		
		return $this->render('locatie/index.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'locaties' => $locaties,
		]);
	}
	
	/**
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, LocatieService $locatieService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		if($huwelijkService->setLocation((int) $id)){
			//$this->addFlash('success', 'Locatie ingesteld');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		else{
			//$this->addFlash('danger', 'Locatie '.$locatie['naam'].' kon niet worden ingesteld');
			return $this->redirect($this->generateUrl('app_locatie_index'));
		}		
		
	}
	
	/**
	 * @Route("/{id}/unset")
	 */
	public function unsetAction(Session $session, $id, LocatieService $locatieService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		if($huwelijkService->removeLocation((int) $id)){
			//$this->addFlash('success', 'Locatie ingesteld');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		else{
			//$this->addFlash('danger', 'Locatie '.$locatie['naam'].' kon niet worden ingesteld');
			return $this->redirect($this->generateUrl('app_locatie_index'));
		}
		
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
