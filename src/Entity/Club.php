<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups('club')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[groups('club')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[groups('club')]
    private ?string $location = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $agent = null;

     #[ORM\ManyToOne(inversedBy: 'clubs')]
    
     #[groups('club')]
     private ?Terrain $terain = null;

     #[ORM\OneToMany(mappedBy: 'club', targetEntity: Avis::class)]
   
     private Collection $avis;

    public function __construct()
    {
        $this->terrains = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }


    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getTerain(): ?Terrain
    {
        return $this->terain;
    }

    public function setTerain(?Terrain $terain): self
    {
        $this->terain = $terain;

        return $this;
    }
    public function __toString() {
        $int = $this->id;
        
        
        return $int;
    }
    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setClub($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getClub() === $this) {
                $avi->setClub(null);
            }
        }

        return $this;
    }

}           
