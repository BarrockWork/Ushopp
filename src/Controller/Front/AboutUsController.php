<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/aboutUs")
 */
class AboutUsController extends AbstractController
{
    /**
     * @Route("/", name="aboutUs")
     */
    public function index(): Response
    {
        return $this->render('about_us/index.html.twig', [
            'aboutUs' => 'AboutUsController',
        ]);
    }
}
