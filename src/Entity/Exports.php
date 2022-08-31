<?php

namespace App\Entity;

use App\Repository\ExportsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExportsRepository::class)]
class Exports
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $exportAt = null;

    #[ORM\Column(length: 255)]
    private ?string $exportUser = null;

    #[ORM\Column(length: 255)]
    private ?string $localName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExportAt(): ?\DateTimeImmutable
    {
        return $this->exportAt;
    }

    public function setExportAt(\DateTimeImmutable $exportAt): self
    {
        $this->exportAt = $exportAt;

        return $this;
    }

    public function getExportUser(): ?string
    {
        return $this->exportUser;
    }

    public function setExportUser(string $exportUser): self
    {
        $this->exportUser = $exportUser;

        return $this;
    }

    public function getLocalName(): ?string
    {
        return $this->localName;
    }

    public function setLocalName(string $localName): self
    {
        $this->localName = $localName;

        return $this;
    }


}
