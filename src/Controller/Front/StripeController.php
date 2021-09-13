<?php

namespace App\Controller\Front;

use App\Entity\Carrier;
use App\Entity\OrderShop;
use Cocur\Slugify\Slugify;
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
        $YOUR_DOMAIN = $this->getParameter('STRIPE_DOMAIN_URL');
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

        Stripe::setApiKey($this->getParameter('STRIPE_API_KEY'));

        // OrderDetails
        foreach($order->getOrderDetails()->getValues() as $orderDetail){
            $tva = $orderDetail->getProduct()->getPrice() * ($orderDetail->getProduct()->getTax()/100);
            $pricettc = round(($orderDetail->getPrice() + $tva),2);
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($pricettc * 100),
                    'product_data' => [
                        'name' => $orderDetail->getProduct()->getName(),
                        'images' => [$YOUR_DOMAIN."/upload/products/thumbnails/".$orderDetail->getProduct()->getImageName()],
                    ],
                ],
                'quantity' => $orderDetail->getQuantity(),
            ];
        }

        // Carrier
        $slugger = new Slugify();
        $nameSlugCarrier = $slugger->slugify($order->getCarrierName());
        $carrier = $em->getRepository(Carrier::class)->findOneByNameSlug($nameSlugCarrier);
        $imageCarrier = $YOUR_DOMAIN;
        if($carrier) {
            $imageCarrier = $YOUR_DOMAIN."/upload/carriers/thumbnails/".$carrier->getImageName();
        }
        $productForStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$imageCarrier],
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
