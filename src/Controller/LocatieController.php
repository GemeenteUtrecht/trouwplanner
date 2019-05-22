<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\LocatieService;
use App\Service\HuwelijkService;
use App\Service\CommonGroundService;

/**
 * @Route("/locaties")
 */
class LocatieController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, LocatieService $locatieService,  CommonGroundService $commonGroundService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
				
		$locatie = null;
		// What if we already have an official?
		if($huwelijk['locatie'] ){
			$locatie=$commonGroundService->getSingle($huwelijk['locatie']);
			return $this->redirect($this->generateUrl('app_locatie_view', ['id'=> (int)$locatie['id']]));
		}	
		
		$locaties = $locatieService->getAll();
		
		return $this->render('locatie/index.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'locaties' => $locaties,
				'locatie' => $locatie,
		]);
	}
	
	/**
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, LocatieService $locatieService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$locatie = $locatieService->getOne($id);
		$huwelijk['locatie'] ="http://locaties.demo.zaakonline.nl".$locatie["@id"];
		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Uw locatie '.$locatie['naam'].' is toegevoegd');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		else{
			$this->addFlash('danger', 'Locatie kon niet worden toegeveogd');
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
		
		
		$huwelijk['locatie'] = null;		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Locatie '.$locatie['naam'].' is ingesteld');
			return $this->redirect($this->generateUrl('app_locatie_index'));
		}
		else{
			$this->addFlash('danger', 'Locatie '.$locatie['naam'].' kon niet worden ingesteld');
			return $this->redirect($this->generateUrl('app_locatie_index'));
		}
		
	}
	
	/**
	 * @Route("/{id}")
	 */
	public function viewAction(Session $session, $id, LocatieService $locatieService,  CommonGroundService $commonGroundService)
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
