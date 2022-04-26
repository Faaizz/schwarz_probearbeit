<?php

namespace App\Controller;

use App\Service\Interfaces\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user')]
    public function index(UserServiceInterface $userService): Response
    {
        $users = $userService->getAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
