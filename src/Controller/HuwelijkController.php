<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\ProductService;
use App\Service\HuwelijkService;
/**
 * @Route("/huwelijk")
 */
class HuwelijkController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, ProductService $productService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$producten = $productService->getAll();
		
		return $this->render('huwelijk/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
				'producten' => $producten,
		]);
	}
	
	/**
	 * @Route("/type/{type}")
	 */
	public function typeAction(Session $session, $type, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$huwelijk['type'] = $type;
		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Type '.$type.' geselecteerd');
		}
		else{
			$this->addFlash('danger', 'Type '.$type.' kon niet worden geselecteerd');
		}
		return $this->redirect($this->generateUrl('app_huwelijk_index'));		
	}
	
	/**
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, ProductService $productService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		if($huwelijkService->setProduct((int) $id)){
			/*@todo ditmoet er dus ook weer uit*/
			// Overwrite voor gratis huwelijken (moet dynamsich worden)
			if((int) $id == 1){
				$huwelijkService->setLocation((int) 1); // Stadskantoor
				$huwelijkService->setOfficial((int) 4); // Toegewezen ambtenaar
			}
			
			$this->addFlash('success', 'Plechtigheid geselecteerd');
			return $this->redirect($this->generateUrl('app_partner_index'));
		}
		else{
			$this->addFlash('danger', 'Plechtigheid kon niet worden geselecteerd');
			return $this->redirect($this->generateUrl('app_huwelijk_index'));
		}
		
	}
	
	/**
	 * @Route("/{id}/unset")
	 */
	public function unsetAction(Session $session, $id, ProductService $productService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		if($huwelijkService->removeProduct((int) $id)){
			
			$this->addFlash('success', 'Plechtigheid geselecteerd');
			return $this->redirect($this->generateUrl('app_datum_index'));
		}
		else{
			$this->addFlash('danger', 'Plechtigheid kon niet worden geselecteerd');
			return $this->redirect($this->generateUrl('app_ambtenaar_index'));
		}
		
	}
	
	/**
	 * @Route("/{id}")
	 */
	public function viewAction(Session $session, $id, ProductService $productService)
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
