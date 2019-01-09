<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Show form for login
     *
     * @Route("/login", name="account_login")
     * @return Response
     *
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Logout
     *
     * @Route("/logout", name="account_logout")
     * @return void
     *
     */
    public function logout()
    {

    }

    /**
     * Show form for subscribe
     *
     * @Route("/inscription", name="account_register")
     * @return Response
     */
    public function registrer(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter'
            );

            return $this->redirectToRoute('account_login');

        }

        return $this->render('account/registration.html.twig', [
            'formRegistration' => $form->createView()
        ]);
    }

    /**
     * Show form for update user profile
     *
     * @Route("/compte/profil", name="account_profile")
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // not mandatory to persist because User exists
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre profil a bien été mis à jour'
            );
        }

        return $this->render('account/profile.html.twig', [
            'formAccount' => $form->createView()
        ]);
    }

    /**
     * Modify password
     *
     * @Route("/compte/mot-de-passe", name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        dump($user);

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // check if new password is equal to old password
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                // handle error
                $form->get('oldPassword')->addError(new FormError("Le mot de passe saisie n'est pas valide"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été mis à jour"
                );

                return $this->redirectToRoute('home');

            }
        }

        return $this->render('account/updatePassword.html.twig', [
            'formUpdate' =>$form->createView()
        ]);
    }
}
