<?php

namespace App\Business;

use App\Entity\PortalPage;
use App\Repository\PortalPageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PortalLogic
{
    private $portalRepository;
    private $validator;
    private $entityManager;

    public function __construct(PortalPageRepository $portalRepository, ValidatorInterface $validator, ManagerRegistry $doctrine)
    {
        $this->portalRepository = $portalRepository;
        $this->validator = $validator;
        $this->entityManager = $doctrine->getManager();
    }

    public function index(): array
    {
        return $this->portalRepository->findAll();
    }

    public function create(string $countryCode, string $pagePath, string $content): PortalPage | ConstraintViolationListInterface
    {
        $portal = new PortalPage();
        $portal->setCountryCode(strtoupper($countryCode));
        $portal->setPagePath(($pagePath));
        $portal->setContent($content);

        $errors = $this->validator->validate($portal);
        if (count($errors) > 0) {
            return $errors;
        }

        $this->portalRepository->add($portal);

        return $portal;
    }

    public function update(int $id, string $pagePath, string $content): PortalPage
    {
        $portal = $this->portalRepository->find($id);
        $portal->setPagePath(strtolower($pagePath));
        $portal->setContent($content);

        $this->entityManager->flush();
        return $portal;
    }

    public function delete(int $id)
    {
        $portal = $this->portalRepository->find($id);
        $this->portalRepository->remove($portal);
    }
}
