<?php

namespace App\Entity;

use App\Repository\GalerieSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalerieSectionRepository::class)]
class GalerieSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, GalerieImage>
     */
    #[ORM\OneToMany(targetEntity: GalerieImage::class, mappedBy: 'section')]
    private Collection $galerieImages;

    public function __construct()
    {
        $this->galerieImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

    /**
     * @return Collection<int, GalerieImage>
     */
    public function getGalerieImages(): Collection
    {
        return $this->galerieImages;
    }

    public function addGalerieImage(GalerieImage $galerieImage): static
    {
        if (!$this->galerieImages->contains($galerieImage)) {
            $this->galerieImages->add($galerieImage);
            $galerieImage->setSection($this);
        }

        return $this;
    }

    public function removeGalerieImage(GalerieImage $galerieImage): static
    {
        if ($this->galerieImages->removeElement($galerieImage)) {
            // set the owning side to null (unless already changed)
            if ($galerieImage->getSection() === $this) {
                $galerieImage->setSection(null);
            }
        }

        return $this;
    }
}
