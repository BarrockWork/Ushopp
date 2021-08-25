<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use App\Form\OrderDetailsType;
use App\Repository\OrderDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order/details")
 */
class OrderDetailsAdminController extends AbstractController
{
    /**
     * @Route("/", name="order_details_index", methods={"GET"})
     */
    public function index(OrderDetailsRepository $orderDetailsRepository): Response
    {
        return $this->render('admin/order_details/index.html.twig', [
            'order_details' => $orderDetailsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_details_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $orderDetail = new OrderDetails();
        $form = $this->createForm(OrderDetailsType::class, $orderDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderDetail);
            $entityManager->flush();

            return $this->redirectToRoute('order_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/order_details/new.html.twig', [
            'order_detail' => $orderDetail,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="order_details_show", methods={"GET"})
     */
    public function show(OrderDetails $orderDetail): Response
    {
        return $this->render('admin/order_details/show.html.twig', [
            'order_detail' => $orderDetail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_details_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrderDetails $orderDetail): Response
    {
        $form = $this->createForm(OrderDetailsType::class, $orderDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/order_details/edit.html.twig', [
            'order_detail' => $orderDetail,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="order_details_delete", methods={"POST"})
     */
    public function delete(Request $request, OrderDetails $orderDetail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderDetail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_details_index', [], Response::HTTP_SEE_OTHER);
    }
}
