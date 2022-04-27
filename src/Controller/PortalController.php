<?php

namespace App\Controller;

use App\Business\PortalLogic;
use App\Repository\PortalPageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/portal')]
class PortalController extends AbstractController
{
    #[Route('/pages', name: 'index_pages')]
    #[IsGranted('ROLE_USER')]
    public function index(PortalLogic $portalLogic): Response
    {
        $portals = $portalLogic->index();

        return $this->render('portal/index.html.twig', [
            'portals' => $portals,
        ]);
    }

    #[Route('/pages/new', name: 'show_create_page', methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function showCreate(): Response
    {
        return $this->render('portal/create.html.twig');
    }

    #[Route('/pages/new', name: 'create_page', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, PortalLogic $portalLogic): Response
    {
        $result = $portalLogic->create($request->request->get('country_code'), $request->request->get('page_path'), $request->request->get('content'));

        if ($result instanceof ConstraintViolationListInterface) {
            return $this->render('error.html.twig', [
                'code' => 400,
                'title' => 'Validation Error',
                'message' => $result->get(0),
                'back' => $this->generateUrl('show_create_page'),
            ]);
        }

        return $this->redirectToRoute('index_pages');
    }

    #[Route('/pages/edit/{id}', name: 'show_update_page', methods: ["GET"])]
    #[IsGranted('ROLE_USER')]
    public function showUpdate(PortalPageRepository $repository, int $id): Response
    {
        $portal = $repository->find($id);

        return $this->render('portal/edit.html.twig', [
            'portal' => $portal,
        ]);
    }

    #[Route('/pages/edit/{id}', name: 'update_page', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, PortalLogic $portalLogic, int $id): Response
    {
        $portal = $portalLogic->update($id, $request->request->get('page_path'), $request->request->get('content'));

        return $this->redirectToRoute('index_pages');
    }

    #[Route('/pages/delete/{id}', name: 'delete_page', methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(PortalLogic $portalLogic, int $id): Response
    {
        $portalLogic->delete($id);

        return $this->redirectToRoute('index_pages');
    }     
}
