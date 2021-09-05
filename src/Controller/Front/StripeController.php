<?php

namespace App\Controller\Front;

use App\Entity\OrderShop;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/order")
 */
class StripeController extends AbstractController
{
    /**
     * Stripe payment
     *
     * Doc: https://stripe.com/docs/checkout/integration-builder
     *
     * @Route("/create-checkout-session/{reference}", name="create_checkout_session", methods={"POST"})
     */
    public function createCheckoutSession(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, $reference)
    {
        $YOUR_DOMAIN = 'http://localhost:8000';
        $productForStripe = [];
        $order = $em->getRepository(OrderShop::class)->findOneByReference($reference);

        // if order not found, redirect to my_order
        if(!$order){
            $this->addFlash(
                'danger',
                $translator->trans('shop.order.messages.orderErrorReference')
            );
            return $this->redirectToRoute('my_order', [], Response::HTTP_SEE_OTHER);
        }

        Stripe::setApiKey('sk_test_51JW0QODvgpZ8FOMoaD1BTwmYqxlS5JRu6v9WuWxn3Ysm2neVTg9DhpO0ff0zpI9mbbdwHwNUX2ia6T76jrYGckAS00Qx77gfur');

        // OrderDetails
        foreach($order->getOrderDetails()->getValues() as $orderDetail){
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $orderDetail->getPrice() * 100,
                    'product_data' => [
                        'name' => $orderDetail->getProduct()->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/products/thumbnails/".$orderDetail->getProduct()->getImageName()],
                    ],
                ],
                'quantity' => $orderDetail->getQuantity(),
            ];
        }

        // Carrier
        $productForStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        // Create session stripe
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $productForStripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN .'/'.$request->getLocale().'/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN .'/'.$request->getLocale().'/order/cancel/{CHECKOUT_SESSION_ID}',
        ]);

        // Save stripeSessionId of the order
        $order->setStripeSessionId($checkout_session->id);
        $em->flush();

        return $this->redirect($checkout_session->url, Response::HTTP_SEE_OTHER);
    }
}
