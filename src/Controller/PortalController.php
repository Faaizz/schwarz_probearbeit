<?php

namespace App\Controller;

use App\Business\PortalLogic;
use App\Repository\PortalRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/portal')]
class PortalController extends AbstractController
{
    #[Route('/legal', name: 'index_imprint')]
    #[IsGranted('ROLE_USER')]
    public function index(PortalLogic $portalLogic): Response
    {
        $portals = $portalLogic->index();

        return $this->render('portal/index.html.twig', [
            'portals' => $portals,
        ]);
    }

    #[Route('/legal/new', name: 'show_create_imprint', methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function showCreate(): Response
    {
        return $this->render('portal/create.html.twig');
    }

    #[Route('/legal/new', name: 'create_imprint', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, PortalLogic $portalLogic): Response
    {
        $result = $portalLogic->create($request->request->get('country_code'), $request->request->get('imprint_link'), $request->request->get('imprint'));

        if ($result instanceof ConstraintViolationListInterface) {
            return $this->render('error.html.twig', [
                'code' => 400,
                'title' => 'Validation Error',
                'message' => $result->get(0),
                'back' => $this->generateUrl('show_create_imprint'),
            ]);
        }

        return $this->redirectToRoute('index_imprint');
    }

    #[Route('/legal/edit/{id}', name: 'show_update_imprint', methods: ["GET"])]
    #[IsGranted('ROLE_USER')]
    public function showUpdate(PortalRepository $repository, int $id): Response
    {
        $portal = $repository->find($id);

        return $this->render('portal/edit.html.twig', [
            'portal' => $portal,
        ]);
    }

    #[Route('/legal/edit/{id}', name: 'update_imprint', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, PortalLogic $portalLogic, int $id): Response
    {
        $portal = $portalLogic->update($id, $request->request->get('imprint_link'), $request->request->get('imprint'));

        return $this->redirectToRoute('index_imprint');
    }

    #[Route('/legal/delete/{id}', name: 'delete_imprint', methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(PortalLogic $portalLogic, int $id): Response
    {
        $portalLogic->delete($id);

        return $this->redirectToRoute('index_imprint');
    }    

    #[Route('/new', name: 'add_page', methods: ["GET"])]
    #[IsGranted('ROLE_USER')]
    public function addPage(): Response
    {
        return $this->render('portal/new.html.twig');
    }    
}
