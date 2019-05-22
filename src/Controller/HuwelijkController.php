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
		
		
		return $this->render('huwelijk/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user
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
			$huwelijk = $session->get('huwelijk');
			$this->addFlash('success', 'Type '.$type.' geselecteerd');
		}
		else{
			$this->addFlash('danger', 'Type '.$type.' kon niet worden geselecteerd');
		}
		return $this->redirect($this->generateUrl('app_partner_index'));		
	}
	
	/**
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, ProductService $productService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$product= $productService->getOne($id);
		$huwelijk['ceremonie']="http://producten-diensten.demo.zaakonline.nl".$product["@id"];
		
		// Beetje fals spelen maar zo zij
		if($product["@id"]=="/producten/1" || $product["@id"]=="/producten/2"){
			$huwelijk['locatie']="http://locaties.demo.zaakonline.nl/locaties/1";
			$huwelijk['ambtenaar']="http://ambtenaren.demo.zaakonline.nl/ambtenaren/4";
		}
		
		if($huwelijkService->updateHuwelijk($huwelijk)){
			$this->addFlash('success', 'Uw plechtigheid '.$product['naam'].' ingesteld');
			return $this->redirect($this->generateUrl('app_datum_index'));
		}
		else{
			$this->addFlash('danger', 'Uw plechtigheid kon niet worden ingesteld');
			return $this->redirect($this->generateUrl('app_product_index'));
		}
	}
	
	/**
	 * @Route("/{id}/unset")
	 */
	public function unsetAction(Session $session, $id, ProductService $productService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$huwelijk['ceremonie'] = null;
		if($huwelijkService->updateHuwelijk($huwelijk)){
			
			$this->addFlash('success', 'Plechtigheid verwijderd');
			return $this->redirect($this->generateUrl('app_product_index'));
		}
		else{
			$this->addFlash('danger', 'Plechtigheid kon niet worden verwijderd');
			return $this->redirect($this->generateUrl('app_product_index'));
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
