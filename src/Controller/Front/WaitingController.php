<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/waiting")
 */
class WaitingController extends AbstractController
{
    /**
     * @Route("/", name="waiting")
     */
    public function index(): Response
    {
        return $this->render('waiting/index.html.twig', [
            'underConstrution' => 'WaitingController',
        ]);
    }
}
