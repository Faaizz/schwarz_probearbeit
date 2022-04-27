<?php

namespace App\Controller;

use App\Entity\Login;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $securityAuthenticator): Response
    {
        $user = new Login();

        $username = $request->request->get('username');
        $plainPassword = $request->request->get('plainPassword');

        if (
            !isset($username) ||
            !isset($plainPassword)
        ) {
            return $this->render('registration/register.html.twig');
        }

        $user->setUsername(
            $username
        );

        $user->setPassword(
        $userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->render('registration/register.html.twig');
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $userAuthenticator->authenticateUser(
            $user,
            $securityAuthenticator,
            $request,
        );

        return $this->redirectToRoute('index_pages');

    }
}
