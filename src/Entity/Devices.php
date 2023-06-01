<?php

namespace App\Entity;

use App\Repository\DevicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevicesRepository::class)]
class Devices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\OneToMany(mappedBy: 'Device', targetEntity: Value::class)]
    private Collection $ValueDevice;

    #[ORM\Column(nullable: true)]
    private ?int $NbClients = null;

    public function __construct()
    {
        $this->ValueDevice = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getValueDevice(): Collection
    {
        return $this->ValueDevice;
    }

    public function addValueDevice(Value $valueDevice): self
    {
        if (!$this->ValueDevice->contains($valueDevice)) {
            $this->ValueDevice->add($valueDevice);
            $valueDevice->setDevice($this);
        }

        return $this;
    }

    public function removeValueDevice(Value $valueDevice): self
    {
        if ($this->ValueDevice->removeElement($valueDevice)) {
            // set the owning side to null (unless already changed)
            if ($valueDevice->getDevice() === $this) {
                $valueDevice->setDevice(null);
            }
        }

        return $this;
    }

    public function getNbClients(): ?int
    {
        return $this->NbClients;
    }

    public function setNbClients(?int $NbClients): self
    {
        $this->NbClients = $NbClients;

        return $this;
    }
}
