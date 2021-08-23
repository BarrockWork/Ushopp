<?php

namespace App\Controller;

use App\Entity\UserAvatar;
use App\Form\UserAvatarType;
use App\Repository\UserAvatarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/avatar")
 */
class UserAvatarController extends AbstractController
{
    /**
     * @Route("/", name="user_avatar_index", methods={"GET"})
     */
    public function index(UserAvatarRepository $userAvatarRepository): Response
    {
        return $this->render('user_avatar/index.html.twig', [
            'user_avatars' => $userAvatarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_avatar_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userAvatar = new UserAvatar();
        $form = $this->createForm(UserAvatarType::class, $userAvatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userAvatar);
            $entityManager->flush();

            return $this->redirectToRoute('user_avatar_index');
        }

        return $this->render('user_avatar/new.html.twig', [
            'user_avatar' => $userAvatar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_avatar_show", methods={"GET"})
     */
    public function show(UserAvatar $userAvatar): Response
    {
        return $this->render('user_avatar/show.html.twig', [
            'user_avatar' => $userAvatar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_avatar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserAvatar $userAvatar): Response
    {
        $form = $this->createForm(UserAvatarType::class, $userAvatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_avatar_index');
        }

        return $this->render('user_avatar/edit.html.twig', [
            'user_avatar' => $userAvatar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_avatar_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserAvatar $userAvatar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userAvatar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userAvatar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_avatar_index');
    }
}
