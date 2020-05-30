<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=MealRepository::class)
 */
class Meal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Command::class, inversedBy="id_meal")
     */
    private $id_command;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="id_meal")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_restaurant;

    

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        return $this;
    }

    //Fonction pour gérer l'upload des images: renomer l'image en BDD, enregistrer l'image en BDD.
    public function fileUpload()
    {
        $newName = $this->renameFile($this-> file -> getClientOriginalName());

        $this-> photo = $newName;

        $this-> file->move(__DIR__ . '/../../public/img/restaurant/logo', $newName);
    }


    public function renameFile($name)
    {
        return 'fichier_' . time() . '_' . rand(1, 9999) . '_' . $name;
    }


    public function removePhoto()
    {
        if(file_exists(__DIR__ . '/../../public/img/restaurant/logo' . $this-> photo) && $this-> photo != 'default.jpg')
        {
            unlink(__DIR__ . '/../../public/img/restaurant/logo' . $this-> photo);
        }
    }

    public function getIdCommand(): ?Command
    {
        return $this->id_command;
    }

    public function setIdCommand(?Command $id_command): self
    {
        $this->id_command = $id_command;

        return $this;
    }

    public function getIdRestaurant(): ?Restaurant
    {
        return $this->id_restaurant;
    }

    public function setIdRestaurant(?Restaurant $id_restaurant): self
    {
        $this->id_restaurant = $id_restaurant;

        return $this;
    }

   
}
