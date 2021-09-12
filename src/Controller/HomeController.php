<?php

namespace App\Controller;

use App\Repository\CarouselRepository;
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
    public function indexTranslate(CarouselRepository $carouselRepository): Response
    {
        // Gets images for the carousel
        $carousels = $carouselRepository->findAll();

        return $this->render('home/index.html.twig', [
            'carousels' => $carousels
        ]);
    }
}
