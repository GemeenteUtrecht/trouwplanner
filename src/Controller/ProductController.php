<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Service\ProductService;
use App\Service\HuwelijkService;
use App\Service\CommonGroundService;

/**
 * @Route("/producten")
 */
class ProductController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, ProductService $productService,  CommonGroundService $commonGroundService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$producten = $productService->getAll();
		
		$ceremonie= null;
		if($huwelijk && $huwelijk['ceremonie']){
			$ceremonie=$commonGroundService->getSingle($huwelijk['ceremonie']);
		}
		
		return $this->render('product/index.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'producten' => $producten,
				'ceremonie' => $ceremonie,
		]);
	}
		
	/**
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, ProductService $productService, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$product= $productService->getOne($id);
		$huwelijk['ceremonie'] = "http://producten-diensten.demo.zaakonline.nl".$product["@id"];
		
		// Beetje fals spelen maar zo zij
		if($huwelijk['ceremonie']=="http://producten-diensten.demo.zaakonline.nl/producten/1"){
			$huwelijk['locatie']=="http://locaties.demo.zaakonline.nl/locaties/1";
			$huwelijk['ambtenaar']=="http://ambtenaren.demo.zaakonline.nl/ambtenaren/4";
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
	 * @Route("/{id}")
	 */
	public function viewAction(Session $session, $id, ProductService $productService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');		
		
		$product= $productService->getOne($id);
		
		return $this->render('product/product.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'product' => $product,
		]);
	}
}
