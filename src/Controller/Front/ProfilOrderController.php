<?php

namespace App\Controller\Front;

use App\Entity\OrderShop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/account")
 */
class ProfilOrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator){
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * Orders of the user
     *
     * @Route("/orders", name="account_orders")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $orders = $this->em->getRepository(OrderShop::class)->findSuccessOrders($user);

        return $this->render('profil/order/index.html.twig', [
            'user' => $user,
            'orders' => $orders
        ]);
    }

    /**
     * Show an order of the user
     *
     * @Route("/order/show/{reference}", name="account_order_show")
     */
    public function show($reference): Response
    {
        $user = $this->getUser();
        $order = $this->em->getRepository(OrderShop::class)->findOneByReference($reference);

        if(!$order || $order->getUser() !== $user){
            return $this->redirectToRoute('account_orders');
        }

        return $this->render('profil/order/show.html.twig', [
            'order' => $order
        ]);
    }
}
