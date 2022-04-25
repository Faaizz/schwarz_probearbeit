<?php

namespace App\Controller;

use App\Business\PortalLogic;
use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PortalController extends AbstractController
{
    #[Route('/portal', name: 'index_portal')]
    public function index(PortalLogic $portalLogic): Response
    {
        $portals = $portalLogic->index();

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
    public function create(Request $request, PortalLogic $portalLogic): Response
    {
        $result = $portalLogic->create($request->request->get('country_code'), $request->request->get('imprint_link'), $request->request->get('imprint'));

        if ($result instanceof ConstraintViolationListInterface) {
            return $this->render('error.html.twig', [
                'code' => 400,
                'title' => 'Validation Error',
                'message' => $result->get(0),
                'back' => $this->generateUrl('show_create_portal'),
            ]);
        }

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
    public function update(Request $request, PortalLogic $portalLogic, int $id): Response
    {
        $portal = $portalLogic->update($id, $request->request->get('imprint_link'), $request->request->get('imprint'));

        return $this->redirectToRoute('index_portal');
    }

    #[Route('/portal/delete/{id}', name: 'delete_portal', methods: ["GET"])]
    public function delete(PortalLogic $portalLogic, int $id): Response
    {
        $portalLogic->delete($id);

        return $this->redirectToRoute('index_portal');
    }    
}
