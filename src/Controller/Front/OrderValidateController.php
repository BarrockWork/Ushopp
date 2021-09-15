<?php

namespace App\Controller\Front;

use App\Classe\Cart;
use App\Classe\Mail;
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

            $this->cart->remove(); // Clear the cart
            $order->setStatus(1); // Order status = validate
            $order->setIsPaid(true);// order is paid
            $order->setPaymentAt(new \DateTime('now')); // Save date of payment
            $this->em->flush();

            // Update the stock
            $this->stockHandler($order);

             // Send an email
            $mail = new Mail();
            $userInfo = $order->getUser()->getFirstname();
            $content = $this->translator->trans('mail.order.success',  ['%user%' => $userInfo]);
            $mail->send(
                $order->getUser()->getEmail(),
                $userInfo,
                $this->translator->trans('mail.order.validate'),
                $content
            );
        }

        return $this->render('order_validate/success.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * Error payment with stripe
     *
     * @Route("/cancel/{stripeSessionId}", name="order_cancel")
     */
    public function index($stripeSessionId): Response
    {
        $order = $this->em->getRepository(OrderShop::class)->findOneByStripeSessionId($stripeSessionId);

        // Redirect homepage if not order or user is wrong
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //Envoyer un email pour l'echec de paiement

        return $this->render('order_validate/cancel.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * Update the stock of products
     * @param OrderShop $orderShop
     */
    private function stockHandler(OrderShop $orderShop) {
        foreach ($orderShop->getOrderDetails() as $orderDetail) {
            $productStock = $orderDetail->getProduct()->getProductStock();
            $quantitySelled = $orderDetail->getQuantity();
            $newStock = $productStock->getQuantity() - $quantitySelled;
            $productStock->setQuantity($newStock);

            if($newStock <= 0) {
                $orderDetail->getProduct()->setActive(false);
            }
            $this->em->flush();
        }
        return true;
    }
}
