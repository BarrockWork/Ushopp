<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\UserAddress;
use App\Form\UserAddressType;
use App\Repository\UserAddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/user/address")
 */
class UserAddressAdminController extends AbstractController
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
     * @Route("/", name="user_address_index", methods={"GET"})
     */
    public function index(UserAddressRepository $userAddressRepository): Response
    {
        return $this->render('admin/user_address/index.html.twig', [
            'user_addresses' => $userAddressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{idUser}/new", name="user_address_new", methods={"GET","POST"})
     */
    public function new(Request $request, int $idUser): Response
    {
        $userAddress = new UserAddress();
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneById($idUser);
        $userAddress->setUser($user);
        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userAddress);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.addAddress')
            );

            return $this->redirectToRoute('admin_user_show', array(
                'id' => $idUser
            ));
        }

        return $this->render('admin/user_address/new.html.twig', [
            'user_address' => $userAddress,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_address_show", methods={"GET"})
     */
    public function show(UserAddress $userAddress): Response
    {
        return $this->render('admin/user_address/show.html.twig', [
            'userAddress' => $userAddress,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_address_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserAddress $userAddress): Response
    {
        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.editAddress')
            );

            return $this->redirectToRoute('admin_user_show', array(
                'id' => $userAddress->getUser()->getId()
            ));
        }

        return $this->render('admin/user_address/edit.html.twig', [
            'userAddress' => $userAddress,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_address_delete")
     */
    public function deleteAddress(UserAddress $userAddress, UserAddressRepository $repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $address = $repo->findBy(['id' => $userAddress->getId()]);

        if ($address) {
            $entityManager->remove($userAddress);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.deleteAddress')
            );
        }

        return $this->redirectToRoute('admin_user_show', array(
            'id' => $userAddress->getUser()->getId()
        ));
    }
}
