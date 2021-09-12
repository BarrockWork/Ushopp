<?php

namespace App\Controller;

use App\Repository\CarouselRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CarouselRepository $carouselRepository, ProductRepository $pr, CategoryRepository $cr): Response
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
