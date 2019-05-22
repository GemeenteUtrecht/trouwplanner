<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use MessageBird\Client as MessageBird;

use App\Service\HuwelijkService;
use App\Service\PersonenService;
use App\Service\CommonGroundService;
use App\Service\NotificationService;

/**
 * @Route("/getuigen")
 */
class GetuigenController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, Request $request, HuwelijkService $huwelijkService, PersonenService $personenServices,  CommonGroundService $commonGroundService, NotificationService $notificationService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$getuigen = [];
		if($huwelijk && $huwelijk['getuigen']){
			foreach($huwelijk['getuigen'] as $getuige){
				$getuigen[]= $commonGroundService->getSingle($getuige);
			}
		}
		
		
		/* @todo we should turn this into symfony form */
		if ($request->isMethod('POST')) {
			// Opstellen bericht
			$key = "KlemtSTIvVWVQRS0QZJIF9qB0";
			$messageBird = new MessageBird($key, new \MessageBird\Common\HttpClient(MessageBird::ENDPOINT, 10, 10));
			
			$persoon['naam']['voornamen'] = $request->request->get('voornamen');
			$persoon['naam']['geslachtsnaam'] = $request->request->get('geslachtsnaam');
			$persoon['emailadres'] = $request->request->get('emailadres');
			$persoon['telefoonnummer'] = $request->request->get('telefoonnummer');
			
			if (!filter_var($persoon['emailadres'], FILTER_VALIDATE_EMAIL)) {				
				$this->addFlash('danger', 'Ongeldig email adres '.$persoon['emailadres']);
				return $this->redirect($this->generateUrl('app_getuigen_index'));
			}
			/*
			if($persoon['telefoonnummer'])
			{
				try {
				$Lookup = $messageBird->lookup->read($persoon['telefoonnummer']);
				//var_dump($Lookup);
				} catch (Exception $e) {
					$this->addFlash('danger', 'Ongeldig telefoonnummer '.$persoon['telefoonnummer'].' probeer een telefoon nummer met alleen cijfers en voorgegaan door 31');
					return $this->redirect($this->generateUrl('app_getuigen_index'));					
				}
			}
			
			*/
			$persoon = $personenServices->create($persoon);
			
			
			if(!$huwelijk['getuigen']){
				$huwelijk['getuigen']=[];
			}
			
			$huwelijk['getuigen'][] = 'http://personen.demo.zaakonline.nl'.$persoon["@id"];
			
			if($huwelijkService->updateHuwelijk($huwelijk)){
				$this->addFlash('success', 'Uw getuige '.$persoon['naam']['voornamen'].' '.$persoon['naam']['geslachtsnaam'].' is uitgenodigd');
				
				/*
				if($persoon['telefoonnummer']){
					$notificationService->sendSMS('Gefeliciteerd u bent uitgenodigd als getuige voor een huwelijk. U kunt via deze link bevestigen http://utrecht.trouwplanner.online/token/adf32t343rfa',$persoon['telefoonnummer']);
				}
			*/
				
				if(count($huwelijk['getuigen'])>=4){
					return $this->redirect($this->generateUrl('app_extra_index'));					
				}
				return $this->redirect($this->generateUrl('app_getuigen_index'));
			}
			else{
				$this->addFlash('danger', 'Getuige kon niet worden uitgenodigd');
				return $this->redirect($this->generateUrl('app_getuigen_index'));
			}
		}
		
		return $this->render('getuigen/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
				'getuigen' => $getuigen,
		]);
	} 
}
