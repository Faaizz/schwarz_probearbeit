<?php

namespace App\Controller;

use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/legal/{_locale<[A-Za-z]{2}>}/{imprint}', name: 'app_imprint')]
    public function imprint(string $_locale, string $imprint, PortalRepository $portalRepo): Response
    {
        $portal = $portalRepo->findOneBy(['countryCode' => strtolower($_locale), 'imprintLink' => strtolower($imprint)]);

        if (!isset($portal)) {
            throw $this->createNotFoundException('The page does not exist');
        }

        return $this->render('imprint/view.html.twig', [
            'portal' => $portal,
        ]);
    }

    #[Route('/', name: 'list_pages')]
    public function default(PortalRepository $portalRepo): Response
    {
        $portals = $portalRepo->findAll();
        $imprintLinks = array_map( fn($portal) => $this->generateUrl('app_imprint', [
                '_locale' => $portal->getCountryCode(),
                'imprint' => $portal->getImprintLink(),
            ] 
        ), $portals);

        return $this->render('index.html.twig', [
            'imprintLinks' => $imprintLinks,
            'usersLink' => $this->generateUrl('app_user'),
        ]);
    }
}
