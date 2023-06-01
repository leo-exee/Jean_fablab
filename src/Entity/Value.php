<?php

namespace App\Entity;

use App\Repository\ValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValueRepository::class)]
class Value
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ValueDevice')]
    
    private ?Devices $Device = null;

    #[ORM\Column]
    private ?int $Count = null;

    #[ORM\Column]
    private ?string $Date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevice(): ?Devices
    {
        return $this->Device;
    }

    public function setDevice(?Devices $Device): self
    {
        $this->Device = $Device;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->Count;
    }

    public function setCount(int $Count): self
    {
        $this->Count = $Count;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->Date;
    }

    public function setDate(string $Date): self
    {
        $this->Date = $Date;

        return $this;
    }
}
