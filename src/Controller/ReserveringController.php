<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\HuwelijkService;
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
	public function sendAction(Session $session, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		
		if($huwelijkService->aanvraag()){
			//$this->addFlash('success', 'Uw reservering is verzonden');
			return $this->redirect($this->generateUrl('app_extra_index'));
		}
		else{
			//$this->addFlash('danger', 'Uw reservering kon niet worden verzonden');
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
			//$this->addFlash('success', 'Uw reservering is geanuleerd');
			return $this->redirect($this->generateUrl('app_reservering_index'));
		}
		else{
			//$this->addFlash('danger', 'Uw reservering kon niet worden geanuleerd');
			return $this->redirect($this->generateUrl('app_reservering_index'));
		}		
	}
}
