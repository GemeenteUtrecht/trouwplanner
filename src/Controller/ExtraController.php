<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\ProductService;
/**
 * @Route("/extras")
 */
class ExtraController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('extra/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/{id}/set")
	 */
	public function setAction(Session $session, $id, ProductService $productService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$product= $productService->getOne($id);
		$huwelijk['extras'][] = "http://producten-diensten.demo.zaakonline.nl".$product["@id"];
		
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
		
		return $this->render('ambtenaar/ambtenaar.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'extra' => $product,
		]);
	}
}
