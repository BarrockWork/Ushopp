<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/account")
 */
class ProfilCommentsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }


    /**
     * @Route("/profil/comments", name="profil_comments")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $comments = $this->em->getRepository(Comment::class)->findByUser($user->getId(), ['createdAt' => 'desc']);

        return $this->render('profil/comment/index.html.twig', [
            'user' => $user,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/comment/{id}/edit", name="profil_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->remove('isModerate');
        $form->remove('product');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('comment.messages.successEdit')
            );

            return $this->redirectToRoute('profil_comments');
        }

        return $this->renderForm('profil/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/comment/{id}/delete", name="profil_comment_delete")
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('comment.messages.successDelete')
            );
        }

        return $this->redirectToRoute('profil_comments', [], Response::HTTP_SEE_OTHER);
    }
}
