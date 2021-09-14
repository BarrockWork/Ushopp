<?php

namespace App\Controller\Front;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/reset_password")
 */
class ResetPasswordController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em) {
        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * Reset password
     *
     * @Route("/", name="reset_password")
     */
    public function resetPassword(Request $request): Response
    {
        // If the user is connected, redirect to his account
        if($this->getUser()) {
            return $this->redirectToRoute('account_index');
        }

        // Check if the email exist
        if($request->get('email')) {
            $user = $this->em->getRepository(User::class)->findOneByEmail($request->get('email'));

            if($user) {
                // Step 1: Enregistrer en base la demande de reset password
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTimeImmutable('now'));
                $this->em->persist($resetPassword);
                $this->em->flush();

                //Step 2: Envoyer un mail avec lien permettant de mettre à jour son mot de passe
                $YOUR_DOMAIN = $this->getParameter('STRIPE_DOMAIN_URL');
                $mail = new Mail();
                $userInfo = $user->getFirstname(). " ".$user->getLastname();
                $urlUpdatePassword = $this->generateUrl('update_lost_password', ['token' =>$resetPassword->getToken()]);
                $url = "<a href=\"".$YOUR_DOMAIN.$urlUpdatePassword."\">".$this->translator->trans('mail.resetPassword.successPart2')."</a>";
                $content = $this->translator->trans('mail.resetPassword.success',  ['%user%' => $userInfo, '%url%' => $url]);
                $mail->send(
                    $user->getEmail(),
                    $userInfo,
                    $this->translator->trans('user.resetPassword.title2'),
                    $content
                );

                $this->addFlash(
                    'success',
                    $this->translator->trans('user.resetPassword.notification')
                );

                return $this->redirectToRoute('reset_password');
            }else{
                $this->addFlash(
                    'warning',
                    $this->translator->trans('user.resetPassword.emailNotExist')
                );
            }
        }

        return $this->render('reset_password/reset.html.twig', [
        ]);
    }

    /**
     * Update after a reset password
     * @Route("/update/{token}", name="update_lost_password")
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface $passwordHasher, $token): Response
    {
        $resetPassword = $this->em->getRepository(ResetPassword::class)->findOneByToken($token);

        if(!$resetPassword) {
            return $this->redirectToRoute('reset_password');
        }

        // Vérifier si le createdAt = now - 3h
        $dateNow = new \DateTimeImmutable('now');

        if($dateNow > $resetPassword->getCreatedAt()->modify('+ 3 hours'))
        {
            // Le token a expiré
            $this->addFlash(
                'warning',
                $this->translator->trans('user.resetPassword.tokenExpired')
            );
            return $this->redirectToRoute('reset_password');
        }else{
            // Creation du formulaire de réinitialisation de mot de passe
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $newPwd = $form->get('newPassword')->getData();
                $password = $passwordHasher->hashPassword($resetPassword->getUser(), $newPwd);
                $resetPassword->getUser()->setPassword($password);
                $this->em->flush();

                $this->addFlash(
                    'success',
                    $this->translator->trans('user.resetPassword.successUpdate')
                );

                return $this->redirectToRoute('app_login');
            }
            return $this->render('reset_password/update.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}
