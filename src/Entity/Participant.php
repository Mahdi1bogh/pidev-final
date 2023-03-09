<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Date participation is required")]
    #[Assert\GreaterThanOrEqual("now")]
    private ?\DateTime $dateP = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?Tournois $tournois = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?User $users = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateP(): ?\DateTime
    {
        return $this->dateP;
    }

    public function setDateP(\DateTime $dateP): self
    {
        $this->dateP = $dateP;

        return $this;
    }

    public function getTournois(): ?Tournois
    {
        return $this->tournois;
    }

    public function setTournois(?Tournois $tournois): self
    {
        $this->tournois = $tournois;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function __toString()
    {
        return $this->getId();
    }
}
