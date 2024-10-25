<?php

namespace App\Entity;

use App\Repository\HospitalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalRepository::class)]
class Hospital
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateBuilding = null;

    /**
     * @var Collection<int, Doctor>
     */
    #[ORM\OneToMany(targetEntity: Doctor::class, mappedBy: 'hospital', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $doctors;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateBuilding(): ?\DateTimeInterface
    {
        return $this->dateBuilding;
    }

    public function setDateBuilding(\DateTimeInterface $dateBuilding): static
    {
        $this->dateBuilding = $dateBuilding;

        return $this;
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctor $doctor): static
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors->add($doctor);
            $doctor->setHospital($this);
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): static
    {
        if ($this->doctors->removeElement($doctor)) {
            // set the owning side to null (unless already changed)
            if ($doctor->getHospital() === $this) {
                $doctor->setHospital(null);
            }
        }

        return $this;
    }
}
