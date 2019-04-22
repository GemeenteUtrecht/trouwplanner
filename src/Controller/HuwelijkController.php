<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\ProductService;
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
	
	
}
