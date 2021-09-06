<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("{_locale}/contact_messages")
 */
class ContactAdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private TranslatorInterface $translator;


    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * List of messages
     *
     * @Route("/", name="admin_contact" , methods={"GET"})
     */
    public function index(): Response
    {
        $contactRepo = $this->em->getRepository(Contact::class);
        return $this->render('admin/contact/index.html.twig', [
            'contactsHandled' => $contactRepo->findByIsHandled(true),
            'contactsNotHandled' => $contactRepo->findByIsHandled(false)
        ]);
    }

    /**
     * Set message handled
     *
     * @Route("/handled/{id}", name="admin_contact_handled", methods={"POST"})
     */
    public function setHandled(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('handled'.$contact->getId(), $request->request->get('_token'))) {
            $contact->setIsHandled(true);
            $contact->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->em->persist($contact);
            $this->em->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('contact.messages.successMessage')
            );
        }

        return $this->redirectToRoute('admin_contact', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Set not handled a message
     *
     * @Route("/notHandled/{id}", name="admin_contact_nothandled", methods={"POST"})
     */
    public function setNotHandled(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('nothandled'.$contact->getId(), $request->request->get('_token'))) {
            $contact->setIsHandled(false);
            $contact->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->em->persist($contact);
            $this->em->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('contact.messages.reactivateMessage')
            );
        }

        return $this->redirectToRoute('admin_contact', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Get length of messages not handled
     *
     * @Route("/length_message/notHandled", name="admin_contact_length" , methods={"GET"})
     */
    public function getMessageLengthNotHandled()
    {
        $nbContacts = $this->em->getRepository(Contact::class)->getLengthMessageNotHandled();

        $response =  new JsonResponse();
        $response->setData([
            'nbContacts' => $nbContacts,
            'textNewMessage' => $this->translator->trans('contact.newMessage', ['%nbMessage%' => $nbContacts])
        ]);
        return $response;
    }
}
