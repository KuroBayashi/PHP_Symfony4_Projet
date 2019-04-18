<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DefibrillatorRepository")
 * @ORM\Table(name="`defibrillators`")
 */
class Defibrillator
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups("info")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups("info")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups("info")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups("info")
     *
     * @Assert\Type(type="string")
     */
    private $note;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups("info")
     *
     * @Assert\Type(type="bool")
     */
    private $available = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="defibrillator", orphanRemoval=true)
     */
    private $maintenances;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Groups("info")
     *
     * @Assert\Type(type="bool")
     */
    private $reported = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilization", mappedBy="defibrillator", orphanRemoval=true)
     */
    private $utilizations;

    public function __construct()
    {
        $this->maintenances = new ArrayCollection();
        $this->utilizations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection|Maintenance[]
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->setDefibrillator($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->contains($maintenance)) {
            $this->maintenances->removeElement($maintenance);
            // set the owning side to null (unless already changed)
            if ($maintenance->getDefibrillator() === $this) {
                $maintenance->setDefibrillator(null);
            }
        }

        return $this;
    }

    public function getReported(): ?bool
    {
        return $this->reported;
    }

    public function setReported(?bool $reported): self
    {
        $this->reported = $reported;

        return $this;
    }

    /**
     * @return Collection|Utilization[]
     */
    public function getUtilizations(): Collection
    {
        return $this->utilizations;
    }

    public function addUtilizations(Utilization $utilization): self
    {
        if (!$this->utilizations->contains($utilization)) {
            $this->utilizations[] = $utilization;
            $utilization->setDefibrillator($this);
        }

        return $this;
    }

    public function removeUtilizations(Utilization $utilization): self
    {
        if ($this->utilizations->contains($utilization)) {
            $this->utilizations->removeElement($utilization);
            // set the owning side to null (unless already changed)
            if ($utilization->getDefibrillator() === $this) {
                $utilization->setDefibrillator(null);
            }
        }

        return $this;
    }

}
