<?php

namespace App\Controller;

use App\Repository\PortalPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/pages/{_locale<[A-Za-z]{2}>}/{pagePath}', name: 'app_content')]
    public function page(string $_locale, string $pagePath, PortalPageRepository $portalRepo): Response
    {
        $portal = $portalRepo->findOneBy(['countryCode' => strtoupper($_locale), 'pagePath' => $pagePath]);

        if (!isset($portal)) {
            throw $this->createNotFoundException('The page does not exist');
        }

        return $this->render('page/view.html.twig', [
            'portal' => $portal,
        ]);
    }

    #[Route('/', name: 'list_pages')]
    public function default(PortalPageRepository $portalRepo): Response
    {
        $portals = $portalRepo->findAll();
        $pagePaths = array_map( fn($portal) => $this->generateUrl('app_content', [
                '_locale' => $portal->getCountryCode(),
                'pagePath' => $portal->getPagePath(),
            ] 
        ), $portals);

        return $this->render('index.html.twig', [
            'pagePaths' => $pagePaths,
            'usersLink' => $this->generateUrl('app_user'),
        ]);
    }
}
