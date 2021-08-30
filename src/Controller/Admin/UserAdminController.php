<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\UserAvatar;
use App\Form\UserAvatarType;
use App\Form\UserType;
use App\Repository\UserAvatarRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/user")
 */
class UserAdminController extends AbstractController
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
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $translator->trans('user.form.addUser')
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $translator->trans('user.form.editUser')
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('user.form.deleteAddress')
            );
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("/avatar/{idUser}/new", name="admin_userAvatar_new", methods={"GET","POST"})
     */
    public function newAvatar(Request $request, int $idUser): Response
    {
        $userAvatar = new UserAvatar();
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneById($idUser);
        $userAvatar->setUser($user);
        $form = $this->createForm(UserAvatarType::class, $userAvatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userAvatar);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('admin.user.addAvatar')
            );
            return $this->redirectToRoute('admin_user_show', array(
                'id' => $user->getId()
            ));
        }

        return $this->renderForm('admin/user/avatar/new.html.twig', [
            'userAvatar' => $userAvatar,
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/avatar/{id}/edit", name="admin_userAvatar_edit", methods={"GET","POST"})
     */
    public function editAvatar(Request $request, UserAvatar $userAvatar): Response
    {

        $form = $this->createForm(UserAvatarType::class, $userAvatar);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('admin.user.editAvatar')
            );

            return $this->redirectToRoute('admin_user_show', array(
                'id' => $userAvatar->getUser()->getId()
            ));
        }

        return $this->renderForm('admin/user/avatar/edit.html.twig', [
            'userAvatar' => $userAvatar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/avatar/{id}/delete", name="admin_userAvatar_delete")
     */
    public function deleteAvatar(UserAvatar $userAvatar, UserAvatarRepository $repo): Response
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

        return $this->redirectToRoute('admin_user_show', array(
            'id' => $userAvatar->getUser()->getId(),
        ));
    }
}
