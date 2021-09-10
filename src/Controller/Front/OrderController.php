<?php

namespace App\Controller\Front;

use App\Classe\Cart;
use App\Entity\OrderDetails;
use App\Entity\OrderShop;
use App\Form\OrderFrontType;
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
class OrderController extends AbstractController
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
     * Checkout my order
     *
     * @Route("/", name="my_order")
     */
    public function index(Cart $cart): Response
    {
        $user = $this->getUser();

        // If the user doesn't have an address, he will be redirected
        if(!$user->getUserAddress()->getValues()){
            $this->addFlash(
                'warning',
                $this->translator->trans('shop.order.messages.noAddress')
            );
            return $this->redirectToRoute('account_new_address');
        }

        $form = $this->createForm(OrderFrontType::class, null, ['user' => $user]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * Save an order before the payment
     * @Route("/resume", name="order_resume", methods={"POST"})
     * @param Request $request
     * @param Cart $cart
     * @return Response
     */
    public function add(Request $request, Cart $cart): Response
    {
        $user = $this->getUser();

        // If the user doesn't have an address, he will be redirected
        if(!$user->getUserAddress()->getValues()){
            $this->addFlash(
                'warning',
                $this->translator->trans('shop.order.messages.noAddress')
            );
            return $this->redirectToRoute('account_new_address');
        }

        $form = $this->createForm(OrderFrontType::class, null, [
            'user' => $user
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dateNow = new \DateTime('now');
            $carrier = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $deliveryContent = $delivery->getFirstname().' '.$delivery->getLastname();
            $deliveryContent .= '<br/>'.$delivery->getPhoneNumber();
            $tvaPerProduct = []; // Pour calculer la somme des tva

            if($delivery->getCompany()){
                $deliveryContent .= '<br/>'.$delivery->getCompany();
            }
            $deliveryContent .= '<br/>'.$delivery->getAddress();
            $deliveryContent .= '<br/>'.$delivery->getOthers();
            $deliveryContent .= '<br/>'.$delivery->getZipCode().' '.$delivery->getCity();
            $deliveryContent .= '<br/>'.$delivery->getCountry();

            $order = new OrderShop();
            $reference = $dateNow->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($user);
            $order->setCreatedAt($dateNow);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDeliveryAddress($deliveryContent);
            $order->setStatus(0);

            $this->em->persist($order);

            foreach($cart->getFull() as $product){
                $tva = $product['product']->getPrice() * ($product['product']->getTax()/100) *($product['quantity']);
                $tvaPerProduct[] = $tva;
                $orderDetails = new OrderDetails();
                $orderDetails->setOrderShop($order);
                $orderDetails->setProduct($product['product']);
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setPriceTtc(round((($product['product']->getPrice() * $product['quantity'])+$tva), 2));
                $this->em->persist($orderDetails);
            }

            $this->em->flush();

            return $this->render('order/add.html.twig', [
                'carrier' => $carrier,
                'cart' => $cart->getFull(),
                'delivery' => $deliveryContent,
                'reference'=> $order->getReference(),
                'tva' => array_sum($tvaPerProduct)
            ]);
        }

        return $this->redirectToRoute('my_cart');
    }
}
