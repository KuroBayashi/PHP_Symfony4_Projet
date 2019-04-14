<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaintenanceRepository")
 */
class Maintenance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Defibrillator", inversedBy="maintenances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $defibrillator;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $done_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type(type="string")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="maintenances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->done_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDefibrillator(): ?Defibrillator
    {
        return $this->defibrillator;
    }

    public function setDefibrillator(?Defibrillator $defibrillator): self
    {
        $this->defibrillator = $defibrillator;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->done_at;
    }

    public function setDoneAt(\DateTimeInterface $done_at): self
    {
        $this->done_at = $done_at;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
