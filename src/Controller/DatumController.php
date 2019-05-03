<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\HuwelijkService;

/**
 * @Route("/datum")
 */
class DatumController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, Request $request, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		/* @todo we should turn this into symfony form */
		if ($request->isMethod('POST')) {
			
			$dateArray = (explode(" ",$request->request->get('datum')));
			$date = strtotime($dateArray[1].' '.$dateArray[2].' '.$dateArray[3]);
			$date = date('Y-m-d',$date);
			
			if($huwelijkService->setDate($date, $request->request->get('tijd'))){
				//$this->addFlash('success', 'Datum en tijd '.$date. ' om '.$huwelijk['tijd'].' geselecteerd');
			}
			else{
				//$this->addFlash('danger', 'Datum en tijd '.$huwelijk['datum']. ' om '.$huwelijk['tijd'].'  kon niet worden geselecteerd');
			}
			
		}
		
		return $this->render('datum/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/set")
	 */
	public function setAction(Session $session, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		//$this->addFlash('success', 'Datum voorkeur opgeslagen');
		
		return $this->redirect($this->generateUrl('app_locatie_index'));
	}
}
