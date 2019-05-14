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
		
		$this->addFlash('success', 'Extra '.$product['naam'].' is toegevoegd');
		
		return $this->render('ambtenaar/ambtenaar.html.twig', [
				'user' => $user,
				'huwelijk' => $huwelijk,
				'extra' => $product,
		]);
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
