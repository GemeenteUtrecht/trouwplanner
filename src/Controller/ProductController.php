<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Service\ProductService;

/**
 * @Route("/producten")
 */
class ProductController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session, ProductService $productService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$producten = $productService->getAll();
		
		return $this->render('product/index.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'producten' => $producten,
		]);
	}
	
	/**
	 * @Route("/{id}")
	 */
	public function viewAction(Session $session, $id, ProductService $productService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('product/product.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'product' => $product,
		]);
	}
}
