<?php

namespace App\Controller\Front;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/signup", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, TranslatorInterface $translator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Check if the email exist
            $searchEmail = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$searchEmail) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                // Send an email
                $mail = new Mail();
                $userInfo = $user->getFirstname(). " ".$user->getLastname();
                $content = $translator->trans('mail.signup.success',  ['%user%' => $userInfo]);
                $mail->send(
                    $user->getEmail(),
                    $userInfo,
                    $translator->trans('mail.welcome'),
                    $content
                );

                // Message flash
                $this->addFlash(
                    'success',
                    $translator->trans('user.messages.successRegister')
                );
                return $this->redirectToRoute('home');

            }else{
                // Message flash
                $this->addFlash(
                    'warning',
                    $translator->trans('mail.signup.errorEmail')
                );
            }
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
