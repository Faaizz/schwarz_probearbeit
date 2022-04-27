<?php

namespace App\Business;

use App\Entity\Portal;
use App\Repository\PortalRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PortalLogic
{
    private $portalRepository;
    private $validator;
    private $entityManager;

    public function __construct(PortalRepository $portalRepository, ValidatorInterface $validator, ManagerRegistry $doctrine)
    {
        $this->portalRepository = $portalRepository;
        $this->validator = $validator;
        $this->entityManager = $doctrine->getManager();
    }

    public function index(): array
    {
        return $this->portalRepository->findAll();
    }

    public function create(string $countryCode, string $imprintLink, string $imprint): Portal | ConstraintViolationListInterface
    {
        $portal = new Portal();
        $portal->setCountryCode(strtoupper($countryCode));
        $portal->setImprintLink(($imprintLink));
        $portal->setImprint($imprint);

        $errors = $this->validator->validate($portal);
        if (count($errors) > 0) {
            return $errors;
        }

        $this->portalRepository->add($portal);

        return $portal;
    }

    public function update(int $id, string $imprintLink, string $imprint): Portal
    {
        $portal = $this->portalRepository->find($id);
        $portal->setImprintLink(strtolower($imprintLink));
        $portal->setImprint($imprint);

        $this->entityManager->flush();
        return $portal;
    }

    public function delete(int $id)
    {
        $portal = $this->portalRepository->find($id);
        $this->portalRepository->remove($portal);
    }
}
