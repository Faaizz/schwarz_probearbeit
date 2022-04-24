<?php

namespace App\Controller;

use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/{_locale}/imprint', name: 'app_imprint', requirements: ['_locale' => 'en|de'])]
    public function imprint(string $_locale, PortalRepository $portalRepo): Response
    {
        $portal = $portalRepo->findOneBy(['countryCode' => strtolower($_locale)]);
        return $this->render('imprint/index.html.twig', [
            'portal' => $portal,
        ]);
    }

}
