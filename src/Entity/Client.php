<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use FontLib\TrueType\Collection;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $clientName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null; // Rue

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $postalCode = null; // Code postal

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null; // Ville

    #[ORM\Column(length: 10)]
    private ?string $phone = null;



    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'client')]
    private \Doctrine\Common\Collections\Collection $invoices;

    public function __toString(): string
    {
        return $this->clientName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): static
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getFullAddress(): string
    {
        return trim(sprintf(
            '%s, %s %s',
            $this->street ?? 'Rue non renseignée',
            $this->postalCode ?? 'Code postal non renseigné',
            $this->city ?? 'Ville non renseignée'
        ));
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }


}
