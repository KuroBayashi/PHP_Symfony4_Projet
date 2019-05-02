<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`users`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"user_info"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Groups({"user_info"})
     *
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"user_info"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({"user_info"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="user", orphanRemoval=true)
     *
     * @Groups({"user_info_expended"})
     */
    private $maintenances;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilization", mappedBy="user", orphanRemoval=true)
     *
     * @Groups({"user_info_expended"})
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $maintenance->setUser($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->contains($maintenance)) {
            $this->maintenances->removeElement($maintenance);
            // set the owning side to null (unless already changed)
            if ($maintenance->getUser() === $this) {
                $maintenance->setUser(null);
            }
        }

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
            $utilization->setUser($this);
        }

        return $this;
    }

    public function removeUtilizations(Utilization $utilization): self
    {
        if ($this->utilizations->contains($utilization)) {
            $this->utilizations->removeElement($utilization);
            // set the owning side to null (unless already changed)
            if ($utilization->getUser() === $this) {
                $utilization->setUser(null);
            }
        }

        return $this;
    }
}
