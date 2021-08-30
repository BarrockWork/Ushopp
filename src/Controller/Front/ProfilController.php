<?php

namespace App\Controller\Front;

use App\Entity\UserAddress;
use App\Entity\UserAvatar;
use App\Form\UserAddressType;
use App\Form\UserAvatarType;
use App\Form\UserType;
use App\Repository\UserAddressRepository;
use App\Repository\UserAvatarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/{_locale}/account")
 */
class ProfilController extends AbstractController
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
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/", name="account_index")
     * @return Response
     */
    public function myAccount()
    {

        $user = $this->getUser();
        return $this->render('profil/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/new/address", name="account_new_address", methods={"GET","POST"})
     */
    public function addAddress(Request $request)
    {
        $userAddress = new UserAddress();

        $user = $this->getUser();

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

            return $this->redirectToRoute('account_index');
        }

        return $this->render('profil/address/new.html.twig', [
            'user_address' => $userAddress,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/address/{id}/edit", name="account_address_edit")
     */
    public function editAddress(Request $request, UserAddress $userAddress): Response
    {
        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                $this->translator->trans('user.form.editAddress')
            );
            return $this->redirectToRoute('account_index');
        }

        return $this->render('profil/address/edit.html.twig', [
            'user_address' => $userAddress,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/address/{id}/delete", name="account_address_delete")
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

        return $this->redirectToRoute('account_index');
    }

    /**
     * Permet de modifier son profil
     * @Route("/edit/profile", name="account_edit_profil")
     * @return Response
     */
    public function editProfil(Request $request)
    {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->remove('roles');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.editUser')
            );
            return $this->redirectToRoute('account_index');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Modifiez le mot de passe
     * @Route("/update-password", name="account_update_password")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez que le oldPassword soit le même que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                //gérer l'erreur
                $form->get('oldPassword')->addError(new FormError('Le mot de passe que vous venez de taper n\'est pas votre mot de passe actuel'));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    'Vous venez de changer de mot de passe'
                );

                return $this->redirectToRoute('homepage');
            }
        }


        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/avatar/new", name="user_avatar_new", methods={"GET","POST"})
     */
    public function newAvatar(Request $request): Response
    {
        $userAvatar = new UserAvatar();
        $user = $this->getUser();
        $userAvatar->setUser($user);
        $form = $this->createForm(UserAvatarType::class, $userAvatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userAvatar);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.addAvatar')
            );
            return $this->redirectToRoute('account_index');
        }

        return $this->renderForm('profil/user_avatar/new.html.twig', [
            'user_avatar' => $userAvatar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/avatar/{id}/edit", name="user_avatar_edit", methods={"GET","POST"})
     */
    public function editAvatar(Request $request, UserAvatar $userAvatar): Response
    {
        $form = $this->createForm(UserAvatarType::class, $userAvatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.editAvatar')
            );

            return $this->redirectToRoute('account_index');
        }

        return $this->renderForm('profil/user_avatar/edit.html.twig', [
            'user_avatar' => $userAvatar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/avatar/{id}/delete", name="user_avatar_delete")
     */
    public function delete(UserAvatar $userAvatar, UserAvatarRepository $repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $avatar = $repo->find($userAvatar->getId());

        if ($avatar) {
            $entityManager->remove($avatar);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.deleteAvatar')
            );
        }

        return $this->redirectToRoute('account_index');
    }
}