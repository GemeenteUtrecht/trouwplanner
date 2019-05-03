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
 * @Route("/partner")
 */
class PartnerController extends AbstractController
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
			$partner['voornamen'] = $request->request->get('voornamen');
			$partner['geslachtsnaam'] = $request->request->get('geslachtsnaam');
			$partner['geslachtsnaam'] = $request->request->get('geslachtsnaam');
			$partner['emailadres'] = $request->request->get('emailadres');
			$partner['telefoonnummer'] = $request->request->get('telefoonnummer');
			
			if($huwelijkService->invitePartner($partner)){
				//$this->addFlash('success', 'Partner uitgenodigd');
				return $this->redirect($this->generateUrl('app_product_index'));
			}
			else{
				//$this->addFlash('danger', 'Partner kon niet worden uitgenodigd');
			}
		}
		
		return $this->render('partner/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	
	/**
	 * @Route("/invite")
	 */
	public function inviteAction(Session $session, HuwelijkService $huwelijkService)
	{		
		//$this->addFlash('success', 'Uw partner is uitgenodigd');		
		
		return $this->redirect($this->generateUrl('app_product_index'));
	}
	
}
