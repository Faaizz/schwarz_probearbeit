<?php

namespace App\Entity;

use App\Repository\PortalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortalRepository::class)]
class Portal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 2)]
    private $countryCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $imprintLink;

    #[ORM\Column(type: 'text')]
    private $imprint;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getImprintLink(): ?string
    {
        return $this->imprintLink;
    }

    public function setImprintLink(string $imprintLink): self
    {
        $this->imprintLink = $imprintLink;

        return $this;
    }

    public function getImprint(): ?string
    {
        return $this->imprint;
    }

    public function setImprint(string $imprint): self
    {
        $this->imprint = $imprint;

        return $this;
    }
}
