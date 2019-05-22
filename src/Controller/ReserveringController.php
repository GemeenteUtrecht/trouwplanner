<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\HuwelijkService;
use App\Service\CommonGroundService;
/**
 * @Route("/reservering")
 */
class ReserveringController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session,  CommonGroundService $commonGroundService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		// What if we already have an official?
		$locatie= null;
		if($huwelijk['locatie'] ){
			$locatie=$commonGroundService->getSingle($huwelijk['locatie']);
		}
		$ambtenaar= null;
		if($huwelijk['ambtenaar'] ){
			$ambtenaar=$commonGroundService->getSingle($huwelijk['ambtenaar']);
		}
		$ceremonie= null;
		if($huwelijk && $huwelijk['ceremonie']){
			$ceremonie=$commonGroundService->getSingle($huwelijk['ceremonie']);
		}
		$getuigen = [];
		if($huwelijk && $huwelijk['getuigen']){
			foreach($huwelijk['getuigen'] as $getuige){
				$getuigen[]= $commonGroundService->getSingle($getuige);
			}
		}
		$partners= [];
		if($huwelijk && $huwelijk['partners']){
			foreach($huwelijk['partners'] as $partner){
				$partners[]= $commonGroundService->getSingle($partner);
			}
		}
		
		return $this->render('reservering/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
				'partners' => $partners,
				'getuigen' => $getuigen,
				'ceremonie' => $ceremonie,
				'locatie' => $locatie,
				'ambtenaar' => $ambtenaar,
				'huwelijk' => $huwelijk,
		]);
	}
	
	/**
	 * @Route("/send")
	 */
	public function sendAction(Session $session, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$huwelijk['aanvraag'] = 'aanvraag-nr-25';
				
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Uw reservering is verzonden');
			return $this->redirect($this->generateUrl('app_melding_index'));
		}
		else{
			$this->addFlash('danger', 'Uw reservering kon niet worden verzonden');
			return $this->redirect($this->generateUrl('app_reservering_index'));
		}		
		
		
	}
	
	/**
	 * @Route("/cancel")
	 */
	public function cancelAction(Session $session, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$huwelijk['aanvraag'] = null;
		$huwelijk['melding'] = null;
		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Uw reservering is geanuleerd');
			return $this->redirect($this->generateUrl('app_reservering_index'));
		}
		else{
			$this->addFlash('danger', 'Uw reservering kon niet worden geanuleerd');
			return $this->redirect($this->generateUrl('app_reservering_index'));
		}		
	}
}
