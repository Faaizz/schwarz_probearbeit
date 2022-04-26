<?php

namespace App\Controller;

use App\Business\PortalLogic;
use App\Repository\PortalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/legal')]
class PortalAPIController extends AbstractController
{
    #[Route('/portal', methods: ['GET'])]
    public function index(PortalLogic $portalLogic): Response
    {
        $portals = $portalLogic->index();

        return $this->json([
            'portals' => $portals,
        ]);
    }

    #[Route('/portal', methods: ['POST'])]
    public function create(Request $request, PortalLogic $portalLogic): Response
    {
        $requestParams = json_decode($request->getContent(), true);
        $result = $portalLogic->create($requestParams['country_code'], $requestParams['imprint_link'], $requestParams['imprint']);

        if ($result instanceof ConstraintViolationListInterface) {
            return $this->json([
                'error' => [
                    'code' => 400,
                    'title' => 'Validation Error',
                    'message' => $result->get(0),
                ],
            ]);
        }

        return $this->json(['portal' => $result]);
    }

    #[Route('/portal/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, PortalRepository $portalRepository, PortalLogic $portalLogic, int $id): Response
    {
        $portal = $portalRepository->find($id);
        if (!isset($portal)) {
            return $this->json([
                'error' => [
                    'code' => 404,
                    'title' => 'Not Found',
                    'message' => 'Portal Not Found',
                ],
            ]);
        }

        $requestParams = json_decode($request->getContent(), true);
        $portal = $portalLogic->update($id, $requestParams['imprint_link'], $requestParams['imprint']);

        return $this->json(['portal' => $portal]);
    }

    #[Route('/portal/{id}', methods: ['DELETE'])]
    public function delete(PortalLogic $portalLogic, int $id): Response
    {
        $portalLogic->delete($id);

        return $this->json(['status' => 'success']);
    }    
}
