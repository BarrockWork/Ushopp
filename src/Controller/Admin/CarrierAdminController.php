<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Form\CarrierType;
use App\Repository\CarrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/carrier")
 */
class CarrierAdminController extends AbstractController
{
    /**
     * @Route("/", name="carrier_index", methods={"GET"})
     */
    public function index(CarrierRepository $carrierRepository): Response
    {
        return $this->render('admin/carrier/index.html.twig', [
            'carriers' => $carrierRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="carrier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $carrier = new Carrier();
        $form = $this->createForm(CarrierType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($carrier);
            $entityManager->flush();

            return $this->redirectToRoute('carrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/carrier/new.html.twig', [
            'carrier' => $carrier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="carrier_show", methods={"GET"})
     */
    public function show(Carrier $carrier): Response
    {
        return $this->render('admin/carrier/show.html.twig', [
            'carrier' => $carrier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="carrier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Carrier $carrier): Response
    {
        $form = $this->createForm(CarrierType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('carrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/carrier/edit.html.twig', [
            'carrier' => $carrier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="carrier_delete", methods={"POST"})
     */
    public function delete(Request $request, Carrier $carrier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($carrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('carrier_index', [], Response::HTTP_SEE_OTHER);
    }
}
