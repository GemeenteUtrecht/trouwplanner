<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\AmbtenaarService;
use App\Service\HuwelijkService;
use App\Service\CommonGroundService;
/**
 * @Route("/ambtenaren")
 */
class AmbtenaarController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, AmbtenaarService $ambtenaarService,  CommonGroundService $commonGroundService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		// What if we already have an official?
		if($huwelijk['ambtenaar'] ){
			$ambtenaar=$commonGroundService->getSingle($huwelijk['ambtenaar']);
			return $this->redirect($this->generateUrl('app_ambtenaar_view', ['id'=> (int)$ambtenaar['id']]));			
		}		
		
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
	public function vooreendagAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('ambtenaar/voor-dag.html.twig', [
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
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, AmbtenaarService $ambtenaarService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');		
				
		$ambtenaar = $ambtenaarService->getOne($id);
		$huwelijk['ambtenaar'] = "http://ambtenaren.demo.zaakonline.nl".$ambtenaar["@id"];
		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Uw ambtenaar '.$ambtenaar['voornamen'].' is uitgenodigd');
			return $this->redirect($this->generateUrl('app_reservering_index'));
		}
		else{
			$this->addFlash('danger', 'Ambtenaar kon niet worden geanuleerd');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		
		
					
		
	}
	
	/**
	 * @Route("/{id}/unset")
	 */
	public function unsetAction(Session $session, $id, AmbtenaarService $ambtenaarService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$huwelijk['ambtenaar'] = null;
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Ambtenaar geanuleerd');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		else{
			$this->addFlash('danger', 'Ambtenaar kon niet worden geanuleerd');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		
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
