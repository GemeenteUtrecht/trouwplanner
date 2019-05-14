<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// The services
use App\Service\ProductService;
use App\Service\HuwelijkService;
use App\Service\AmbtenaarService;
use App\Service\LocatieService;
use App\Service\BerichtenService;
use App\Service\ResourceService;
use App\Service\TrouwenService;
use App\Service\AgendaService;

// The forms
use App\Form\AmbtenaarType;
use App\Form\LocatieType;
use App\Form\ProductType;
use App\Form\HuwelijkType;



/**
 * @Route("/beheer")
 */
class BeheerController extends AbstractController
{
	/**
	 * @Route("/")
	 */
	public function indexAction(Session $session, HuwelijkService $huwelijkService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/index.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
	
	/**
	 * @Route("/agenda/{id}")
	 */
	public function agendaAction(Request $request, Session $session, $id, AgendaService $agendaService)
	{
		$ambtenaar = $ambtenaarService->getOne($id);
		
		return $this->render('beheer/agenda.html.twig', [
				'agenda' => $agenda
		]);
	}
	
	/**
	* @Route("/ambtenaren")
	*/
	public function ambtenarenAction(Session $session, AmbtenaarService $ambtenaarService)
	{
		$ambtenaren= $ambtenaarService->getAll();
		
		return $this->render('beheer/ambtenaren.html.twig', [
				'ambtenaren' => $ambtenaren
		]);
	}
	
	/**
	 * @Route("/ambtenaar/{id}")
	 */
	public function ambtenaarAction(Request $request, Session $session, $id, AmbtenaarService $ambtenaarService)
	{
		$ambtenaar = $ambtenaarService->getOne($id);		
		$form = $this->createForm(AmbtenaarType::class, $ambtenaar);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid())
		{
			$ambtenaar = $form->getData();
			$ambtenaar = $ambtenaarService->Save($ambtenaar);
			
			
			$this->addFlash('success', 'Ambtenaar bijgewerkt');
			return $this->redirectToRoute('app_beheer_ambtenaar',['id'=>$id]);
		}
		elseif($form->isSubmitted() && !$form->isValid())
		{
			
			$this->addFlash('danger', 'Ambtenaar <u>niet</u> bijgewerkt');
			return $this->redirectToRoute('app_beheer_ambtenaar',['id'=>$id]);
		}
		
		return $this->render('beheer/ambtenaar.html.twig', [
				'ambtenaar' => $ambtenaar,
				'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/locaties")
	 */
	public function locatiesAction(Session $session, LocatieService $locatieService)
	{
		
		$locaties = $locatieService->getAll();
		
		return $this->render('beheer/locaties.html.twig', [
				'locaties' => $locaties
		]);
	}
	
	/**
	 * @Route("/locatie/{id}")
	 */
	public function locatieAction(Request $request, Session $session, $id, LocatieService $locatieService)
	{
		
		$locatie= $locatieService->getOne($id);
		$form = $this->createForm(LocatieType::class, $locatie);
		
		$form->handleRequest($request);
		
		var_dump($form->getErrors());
		
		if ($form->isSubmitted() && $form->isValid())
		{
			$locatie= $form->getData();
			$locatie= $locatieService->Save($locatie);
			
			
			$this->addFlash('success', 'Locatie bijgewerkt');
			return $this->redirectToRoute('app_beheer_locatie',['id'=>$id]);
		}
		elseif($form->isSubmitted() && !$form->isValid())
		{
			
			$this->addFlash('danger', 'Locatie <u>niet</u> bijgewerkt');
			return $this->redirectToRoute('app_beheer_locatie',['id'=>$id]);
		}
		
		return $this->render('beheer/locatie.html.twig', [
				'locatie' => $locatie,
				'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/huwelijken")
	 */
	public function huwelijkenAction(Session $session, HuwelijkService $huwelijkService)
	{
		
		$huwelijken = $huwelijkService->getAll();
		
		return $this->render('beheer/huwelijken.html.twig', [
				'huwelijken' => $huwelijken
		]);
	}
	
	/**
	 * @Route("/huwelijk/{id}")
	 */
	public function huwelijkAction(Request $request,Session $session, $id, HuwelijkService $huwelijkService)
	{
		$huwelijk = $huwelijkService->getOne($id);
		$form = $this->createForm(HuwelijkType::class, $huwelijk);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) 
		{
			$huwelijk = $form->getData();
			$huwelijk= $huwelijkService->Save($huwelijk);
			
			
			$this->addFlash('success', 'Huwelijk bijgewerkt');
			return $this->redirectToRoute('app_beheer_huwelijk',['id'=>locatie]);
		}
		elseif($form->isSubmitted() && !$form->isValid())
		{
			
			$this->addFlash('danger', 'Huwelijk <u>niet</u> bijgewerkt');
			return $this->redirectToRoute('app_beheer_huwelijk',['id'=>locatie]);
		}
		
		
		return $this->render('beheer/huwelijk.html.twig', [
				'huwelijk' => $huwelijk,
				'form' => $form->createView(),
		]);
	}
		
	/**
	 * @Route("/berichten")
	 */
	public function berichtenAction(Request $request,Session $session, BerichtenService $berichtenService)
	{
		
		$berichten = $berichtenService->getAll();
		
		return $this->render('beheer/berichten.html.twig', [
				'berichten' => $berichten
		]);
	}
	
	/**
	 * @Route("/bericht/{id}")
	 */
	public function berichtAction(Request $request,Session $session, $id, BerichtenService $berichtenService)
	{
		
		$bericht = $berichtenService->getOne($id);
		
		return $this->render('beheer/bericht.html.twig', [
				'bericht' => $bericht
		]);
	}
	
	/**
	 * @Route("/paginas")
	 */
	public function paginasAction(Session $session, ResourceService $resourceService)
	{
		$paginas= $resourceService->getAll();
		
		return $this->render('beheer/paginas.html.twig', [
				'paginas' => $paginas,
		]);
	}
	
	/**
	 * @Route("/pagina/{id}")
	 */
	public function paginaAction(Request $request,Session $session, $id, ResourceService $resourceService)
	{
		
		$pagina = $resourceService->getOne($id);
		$form = $this->createForm(PaginaType::class, $pagina);
		
		return $this->render('beheer/pagina.html.twig', [
				'pagina' => $pagina,
				'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/producten")
	 */
	public function productenAction(Session $session, ProductService $productService)
	{
		$producten = $productService->getAll();
		
		return $this->render('beheer/producten.html.twig', [
				'producten' => $producten
		]);
	}
	
	/**
	 * @Route("/product/{id}")
	 */
	public function productAction(Request $request, Session $session, $id, ProductService $productService)
	{
		$product = $productService->getOne($id);
		$form = $this->createForm(ProductType::class, $product);
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid())
		{
			$product= $form->getData();
			$product= $productService->Save($product);
			
			
			$this->addFlash('success', 'Product bijgewerkt');
			return $this->redirectToRoute('app_beheer_product',['id'=>$id]);
		}
		elseif($form->isSubmitted() && !$form->isValid())
		{
			
			$this->addFlash('danger', 'Product <u>niet</u> bijgewerkt');
			return $this->redirectToRoute('app_beheer_product',['id'=>$id]);
		}
		
		return $this->render('beheer/product.html.twig', [
				'product' => $product,
				'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/instellingen")
	 */
	public function instellingenAction(Session $session, TrouwenService $trouwenService)
	{
		$huwelijk = $session->get('huwelijk');
		$user = $session->get('user');
		
		return $this->render('beheer/instellingen.html.twig', [
				'huwelijk' => $huwelijk,
				'user' => $user,
		]);
	}
}
