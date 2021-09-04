<?php

namespace App\Controller\Front;

use App\Classe\Cart;
use App\Entity\OrderDetails;
use App\Entity\OrderShop;
use App\Form\OrderFrontType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/order")
 */
class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
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
            return $this->redirectToRoute('account_new_address');
        }

        $form = $this->createForm(OrderFrontType::class, null, ['user' => $user]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * Save an order
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
                $orderDetails = new OrderDetails();
                $orderDetails->setOrderShop($order);
                $orderDetails->setProduct($product['product']);
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setPriceTtc($product['product']->getPrice() * $product['quantity']);
                $this->em->persist($orderDetails);
            }
//            $this->em->flush();

            return $this->render('order/add.html.twig', [
                'carrier' => $carrier,
                'cart' => $cart->getFull(),
                'delivery' => $deliveryContent,
                'reference'=> $order->getReference()
            ]);
        }

        return $this->redirectToRoute('my_cart');
    }
}
