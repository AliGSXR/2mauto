<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(length: 255)]
    private ?string $clientName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $plaqueImmatriculation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $totalHTC = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $tva = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $totalTTC = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $details = null;

    /**
     * @var Collection<int, ServiceFact>
     */
    #[ORM\OneToMany(targetEntity: ServiceFact::class, mappedBy: 'invoice', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $serviceFacts;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private ?Client $client = null;

    #[ORM\ManyToMany(targetEntity: ServiceOption::class)]
    private Collection $services;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateEcheance = null;

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTimeInterface $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->serviceFacts = new ArrayCollection();
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(ServiceOption $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
        }

        return $this;
    }

    public function removeService(ServiceOption $service): self
    {
        $this->services->removeElement($service);

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        // Si un client est défini, mettez à jour le champ clientName
        if ($client !== null) {
            $this->clientName = $client->getClientName();
            $this->street = $client->getStreet() ?? 'Rue non renseignée';
            $this->postalCode = $client->getPostalCode() ?? 'Code postal non renseigné';
            $this->city = $client->getCity() ?? 'Ville non renseignée';
        }

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
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
        $addressParts = array_filter([$this->street, $this->postalCode, $this->city]);
        return implode(', ', $addressParts);
    }

    public function getPlaqueImmatriculation(): ?string
    {
        return $this->plaqueImmatriculation;
    }

    public function setPlaqueImmatriculation(string $plaqueImmatriculation): static
    {
        $this->plaqueImmatriculation = $plaqueImmatriculation;

        return $this;
    }

    public function getTotalHTC(): ?string
    {
        return $this->totalHTC;
    }

    public function setTotalHTC(string $totalHTC): static
    {
        $this->totalHTC = $totalHTC;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getTotalTTC(): ?string
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(string $totalTTC): static
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return Collection<int, ServiceFact>
     */
    public function getServiceFacts(): Collection
    {
        return $this->serviceFacts;
    }

    public function addServiceFact(ServiceFact $serviceFact): static
    {
        if (!$this->serviceFacts->contains($serviceFact)) {
            $this->serviceFacts->add($serviceFact);
            $serviceFact->setInvoice($this);
        }

        return $this;
    }

    public function setServiceFacts(Collection $serviceFacts): self
    {
        $unique = new ArrayCollection();

        foreach ($serviceFacts as $serviceFact) {
            if (!$unique->contains($serviceFact)) {
                $unique->add($serviceFact);
            }
        }

        $this->serviceFacts = $unique;

        return $this;
    }

    public function removeServiceFact(ServiceFact $serviceFact): static
    {
        if ($this->serviceFacts->removeElement($serviceFact)) {
            // set the owning side to null (unless already changed)
            if ($serviceFact->getInvoice() === $this) {
                $serviceFact->setInvoice(null);
            }
        }

        return $this;
    }

    public function calculateHTC(): ?float{
        $totalHTC = 0;
        foreach ($this->getServiceFacts() as $serviceFact) {
            $totalHTC += $serviceFact->getUnitPrix() * $serviceFact->getQuantity();
        }
        return $totalHTC;
    }

    public function calculateTva(float $tauxTVA = 20): float
    {
        return $this->calculateHTC() * ($tauxTVA / 100);
    }

    public function calculateTotalTTC(float $tauxTVA = 20): float
    {
        return $this->calculateHTC() + $this->calculateTva($tauxTVA);
    }

}
