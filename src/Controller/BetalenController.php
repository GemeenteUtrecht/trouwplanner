<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Service\CommonGroundService;

/**
 * @Route("/betalen")
 */
class BetalenController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction(Session $session,  CommonGroundService $commonGroundService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		$product = null;
		if($huwelijk && $huwelijk['ceremonie']){
			$product=$commonGroundService->getSingle($huwelijk['ceremonie']);
		}
		
		return $this->render('betalen/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
				'product' => $product,
		]);
	}
}
