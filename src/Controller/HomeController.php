<?php

namespace App\Controller;

use App\Repository\CarouselRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_default")
     */
    public function index(Request $request): Response
    {
        $lang = $request->getLocale();
        $currentUrlTranslate = $this->generateUrl('home', ['_locale' => $lang]);

        return $this->redirect($currentUrlTranslate);
    }

    /**
     * @Route("/{_locale}/", name="home")
     */
    public function indexTranslate(CarouselRepository $carouselRepository, ProductRepository $pr, CategoryRepository $cr): Response
    {
        // Gets images for the carousel
        $carousels = $carouselRepository->findAll();
        $products = $pr->findByisBest(8);
        $categories = $cr->findByActiveTrue();
        // dd($products);

        return $this->render('home/index.html.twig', [
            'carousels' => $carousels,
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
