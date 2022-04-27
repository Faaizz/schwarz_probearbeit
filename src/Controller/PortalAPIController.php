<?php

namespace App\Controller;

use App\Business\PortalLogic;
use App\Repository\PortalPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/portal')]
class PortalAPIController extends AbstractController
{
    #[Route('/pages', methods: ['GET'])]
    public function index(PortalLogic $portalLogic): Response
    {
        $portals = $portalLogic->index();

        return $this->json([
            'portals' => $portals,
        ]);
    }

    #[Route('/pages', methods: ['POST'])]
    public function create(Request $request, PortalLogic $portalLogic): Response
    {
        $requestParams = json_decode($request->getContent(), true);
        $result = $portalLogic->create($requestParams['country_code'], $requestParams['page_path'], $requestParams['content']);

        if ($result instanceof ConstraintViolationListInterface) {
            return $this->json([
                'error' => [
                    'code' => 400,
                    'title' => 'Validation Error',
                    'message' => $result->get(0),
                ],
            ], 400);
        }

        return $this->json(['portal' => $result], 201);
    }

    #[Route('/pages/{id}', methods: ['PUT'])]
    public function update(Request $request, PortalPageRepository $portalRepository, PortalLogic $portalLogic, int $id): Response
    {
        $portal = $portalRepository->find($id);
        if (!isset($portal)) {
            return $this->json([
                'error' => [
                    'code' => 404,
                    'title' => 'Not Found',
                    'message' => 'PortalPage Not Found',
                ],
            ], 404);
        }

        $requestParams = json_decode($request->getContent(), true);
        $portal = $portalLogic->update($id, $requestParams['page_path'], $requestParams['content']);

        return $this->json(['portal' => $portal]);
    }

    #[Route('/pages/{id}', methods: ['DELETE'])]
    public function delete(PortalLogic $portalLogic, PortalPageRepository $portalRepository, int $id): Response
    {
        $portal = $portalRepository->find($id);
        if (!isset($portal)) {
            return $this->json([
                'error' => [
                    'code' => 404,
                    'title' => 'Not Found',
                    'message' => 'PortalPage Not Found',
                ],
            ], 404);
        }

        $portalLogic->delete($id);

        return $this->json(['status' => 'success']);
    }    
}
