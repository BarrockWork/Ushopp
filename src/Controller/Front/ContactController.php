<?php

namespace App\Controller\Front;

use App\Classe\Mail;
use App\Entity\Company;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/contact")
 */
class ContactController extends AbstractController
{
    /**
     * Contact page
     *
     * @Route("/", name="contact_us")
     */
    public function index(Request $request, TranslatorInterface $translator, EntityManagerInterface $em): Response
    {
        // Get company infos
        $company = $em->getRepository(Company::class)->findAll();
        if($company && count($company)>0){
            $company = $company[0];
        }else{
            $company = null;
        }

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            // Persist
            $em->persist($contact);
            $em->flush();

            // Send an email
            $mail = new Mail();
            $userInfo = $contact->getFirstname(). " ".$contact->getLastname();
            $content = $translator->trans('mail.contact.notification',  ['%user%' => $userInfo]);
            $mail->send(
                $contact->getEmail(),
                $userInfo,
                $translator->trans($contact->getSubject()),
                $content
            );

            $this->addFlash(
                'info',
                $translator->trans('contact.notification')
            );
            return $this->redirectToRoute('contact_us');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'company' => $company
        ]);
    }
}
