<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\ProductService;
/**
 * @Route("/beheer")
 */
class BeheerController extends AbstractController
{
	/**
	 * @Route("/")
	 */
	public function indexAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	* @Route("/ambtenaren")
	*/
	public function ambtenarenAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/melding.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/locaties")
	 */
	public function locatiesAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/naamsgrebruik.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/huwelijken")
	 */
	public function huwelijkenAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/getuigen.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
		
	/**
	 * @Route("/berichten")
	 */
	public function berichtenAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/getuigen.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
		
	/**
	 * @Route("/vormgeving")
	 */
	public function vormgevingAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/getuigen.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/organisatie")
	 */
	public function organisatieAction(Session $session)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/getuigen.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
}
