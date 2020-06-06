<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $price;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="id_command")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_user;

    /**
     * @ORM\OneToMany(targetEntity=Meal::class, mappedBy="id_command")
     */
    private $id_meal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="commands2")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_restaurant2;

    public function __construct()
    {
        $this->id_meal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        //$this->id_command = $id_user;
        $this->id_user = $id_user;
        return $this;
    }

    /**
     * @return Collection|Meal[]
     */
    public function getIdMeal(): Collection
    {
        return $this->id_meal;
    }

    public function addIdMeal(Meal $idMeal): self
    {
        if (!$this->id_meal->contains($idMeal)) {
            $this->id_meal[] = $idMeal;
            $idMeal->setIdCommand($this);
        }

        return $this;
    }

    public function removeIdMeal(Meal $idMeal): self
    {
        if ($this->id_meal->contains($idMeal)) {
            $this->id_meal->removeElement($idMeal);
            // set the owning side to null (unless already changed)
            if ($idMeal->getIdCommand() === $this) {
                $idMeal->setIdCommand(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdRestaurant2(): ?Restaurant
    {
        return $this->id_restaurant2;
    }

    public function setIdRestaurant2(?Restaurant $id_restaurant2): self
    {
        $this->id_restaurant2 = $id_restaurant2;

        return $this;
    }

}
