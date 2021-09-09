<?php

namespace App\Controller\Admin;

use App\Entity\OrderShop;
use App\Form\OrderType;
use App\Repository\OrderShopRepository;
use ContainerKNoywj2\getOrderDetailsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/order")
 */
class OrderShopAdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_order_index", methods={"GET"})
     */
    public function index(OrderShopRepository $orderShopRepository): Response
    {
        $ordersPaid = $orderShopRepository->findBy(['isPaid' => true], ['createdAt'=> 'DESC']);
        $ordersNotPaid = $orderShopRepository->findBy(['isPaid' => false], ['createdAt'=> 'DESC']);

        return $this->render('admin/order/index.html.twig', [
            'ordersPaid' => $ordersPaid,
            'ordersNotPaid' => $ordersNotPaid
        ]);
    }

    /**
     * @Route("/{id}", name="admin_order_show", methods={"GET"})
     */
    public function show(OrderShop $order): Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrderShop $order): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }
}