<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="UtilizationRepository")
 * @ORM\Table(name="`utilizations`")
 */
class Utilization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"u_info"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="utilizations")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"u_info_expended"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"u_info"})
     */
    private $doneAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Defibrillator", inversedBy="utilizations")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"u_info_expended"})
     */
    private $defibrillator;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->doneAt;
    }

    public function setDoneAt(\DateTimeInterface $doneAt): self
    {
        $this->doneAt = $doneAt;

        return $this;
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
}
