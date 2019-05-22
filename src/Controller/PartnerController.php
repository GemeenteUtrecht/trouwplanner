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
 * @Route("/partner")
 */
class PartnerController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, Request $request, HuwelijkService $huwelijkService, PersonenService $personenService,  CommonGroundService $commonGroundService, NotificationService $notificationService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$partners= [];
		if($huwelijk && $huwelijk['partners']){
			foreach($huwelijk['partners'] as $partner){
				$partners[]= $commonGroundService->getSingle($partner);
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
				return $this->redirect($this->generateUrl('app_partner_index'));
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
			$persoon = $personenService->create($persoon);
						
			$huwelijk['partners'][] = 'http://personen.demo.zaakonline.nl'.$persoon["@id"];
			
			if($huwelijkService->updateHuwelijk($huwelijk)){
				$this->addFlash('success', 'Uw partner '.$persoon['naam']['voornamen'].' '.$persoon['naam']['geslachtsnaam'].' is uitgenodigd');
				
				/*
				if($persoon['telefoonnummer']){
					$notificationService->sendSMS('Gefeliciteerd u bent uitgenodigd als partner voor een huwelijk. U kunt via deze link bevestigen http://utrecht.trouwplanner.online/token/adf32t343rfa',$persoon['telefoonnummer']);
				}
			*/
				return $this->redirect($this->generateUrl('app_product_index'));
			}
			else{
				$this->addFlash('danger', 'Partner kon niet worden uitgenodigd');
			}
		}
		
		return $this->render('partner/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
				'partners' => $partners,
		]);
	}
	
	
	/**
	 * @Route("/update")
	 */
	public function updateAction(Session $session ,Request $request,HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		var_dump($huwelijk);
		$huwelijk['partners'][0]['persoon']['emailadres'] = $request->request->get('emailadres');
		$huwelijk['partners'][0]['persoon']['telefoonnummer'] = $request->request->get('telefoonnummer');
		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$huwelijk = $session->get('huwelijk');
			$this->addFlash('success', 'Uw gegevens zijn bijgewerkt');
		}
		else{
			$this->addFlash('danger', 'Uw gegevens konden niet worden bijgewerkt');
		}
		
		return $this->redirect($this->generateUrl('app_partner_index'));
	}
}
