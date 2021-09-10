<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Form\CarrierType;
use App\Repository\CarrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/carrier")
 */
class CarrierAdminController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="admin_carrier_index", methods={"GET"})
     */
    public function index(CarrierRepository $carrierRepository): Response
    {
        return $this->render('admin/carrier/index.html.twig', [
            'carriers' => $carrierRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_carrier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $carrier = new Carrier();
        $carrier->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(CarrierType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($carrier);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('carrier.form.addCarrier')
            );

            return $this->redirectToRoute('admin_carrier_index');
        }

        return $this->renderForm('admin/carrier/new.html.twig', [
            'carrier' => $carrier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_carrier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Carrier $carrier): Response
    {
        $form = $this->createForm(CarrierType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('carrier.form.editCarrier')
            );

            return $this->redirectToRoute('admin_carrier_index');
        }

        return $this->renderForm('admin/carrier/edit.html.twig', [
            'carrier' => $carrier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_carrier_delete")
     */
    public function delete(Request $request, Carrier $carrier, CarrierRepository $repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $transporteur = $repo->findBy(['id' => $carrier->getId()]);

        if ($transporteur) {
            $entityManager->remove($carrier);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('carrier.form.deleteCarrier')
            );
        }

        return $this->redirectToRoute('admin_carrier_index');
    }
}
