<?php

namespace App\Controller;

use App\Entity\Portal;
use App\Repository\PortalRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PortalController extends AbstractController
{
    #[Route('/portal', name: 'index_portal')]
    public function index(PortalRepository $repository): Response
    {
        $portals = $repository->findAll();

        return $this->render('portal/index.html.twig', [
            'portals' => $portals,
        ]);
    }

    #[Route('/portal/new', name: 'show_create_portal', methods: ["GET"])]
    public function showCreate(): Response
    {
        return $this->render('portal/create.html.twig');
    }

    #[Route('/portal/new', name: 'create_portal', methods: ["POST"])]
    public function create(Request $request, PortalRepository $repository, ValidatorInterface $validator): Response
    {
        $portal = new Portal();
        $portal->setCountryCode(
            strtolower($request->request->get('country_code'))
        );
        $portal->setImprintLink(
            strtolower($request->request->get('imprint_link'))
        );
        $portal->setImprint($request->request->get('imprint'));

        $errors = $validator->validate($portal);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $repository->add($portal);

        return $this->redirectToRoute('index_portal');
    }

    #[Route('/portal/edit/{id}', name: 'show_update_portal', methods: ["GET"])]
    public function showUpdate(PortalRepository $repository, int $id): Response
    {
        $portal = $repository->find($id);

        return $this->render('portal/edit.html.twig', [
            'portal' => $portal,
        ]);
    }

    #[Route('/portal/edit/{id}', name: 'update_portal', methods: ["POST"])]
    public function update(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator, int $id): Response
    {
        $portal = $doctrine->getRepository(Portal::class)->find($id);
        $portal->setImprintLink(
            strtolower($request->request->get('imprint_link'))
        );
        $portal->setImprint($request->request->get('imprint'));

        $errors = $validator->validate($portal);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('index_portal');
    }

    #[Route('/portal/delete/{id}', name: 'delete_portal', methods: ["GET"])]
    public function delete(PortalRepository $repository, int $id): Response
    {
        $portal = $repository->find($id);
        $repository->remove($portal);

        return $this->redirectToRoute('index_portal');
    }    
}
