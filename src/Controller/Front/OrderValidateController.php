<?php

namespace App\Controller\Front;

use App\Classe\Cart;
use App\Entity\OrderShop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/order")
 */
class OrderValidateController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Cart
     */
    private $cart;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, Cart $cart){
        $this->em = $em;
        $this->translator = $translator;
        $this->cart = $cart;
    }

    /**
     * Success payment with stripe
     *
     * @Route("/success/{stripeSessionId}", name="order_success")
     */
    public function success($stripeSessionId): Response
    {
        $order = $this->em->getRepository(OrderShop::class)->findOneByStripeSessionId($stripeSessionId);

        // Redirect homepage if not order or user is wrong
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute('home');
        }

        // Set isPaid = true
        if(!$order->getStatus()){
//            $this->cart->removeAll(); // Clear the cart
            $order->setStatus(1); // Order status = validate
            $order->setIsPaid(true);// order is paid
            $order->setPaymentAt(new \DateTime('now')); // Save date of payment
            $this->em->flush();

            //Envoyer un email à notre client pour lui confirmer la commande
            //Send email
//            $mail = new Mail();
//            $content = "Bonjour ".$order->getUser()->getFirstname()."<br>Merci pour votre commande !";
//            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande est bien validée !', $content);
        }

        return $this->render('order_validate/success.html.twig', [
            'order' => $order
        ]);
    }
}
