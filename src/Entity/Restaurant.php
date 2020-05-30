<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo='default.png';
    
    private $file;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="id_restaurant", cascade={"persist", "remove"})
     */
    private $id_user;

    /**
     * @ORM\OneToMany(targetEntity=Meal::class, mappedBy="id_restaurant")
     */
    private $id_meal;

    public function __construct()
    {
        $this->id_meal = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

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
    
            $this-> logo = $newName;

            $this-> file->move(__DIR__ . '/../../public/img/restaurant/logo', $newName);
        }
    

        public function renameFile($name)
        {
            return 'fichier_' . time() . '_' . rand(1, 9999) . '_' . $name;
        }
    

        public function removeLogo()
        {
            if(file_exists(__DIR__ . '/../../public/img/restaurant/logo' . $this-> logo) && $this-> logo != 'default.jpg')
            {
                unlink(__DIR__ . '/../../public/img/restaurant/logo' . $this-> logo);
            }
        }


        //Fonction pour gérer l'upload des images: renomer l'image en BDD, enregistrer l'image en BDD.
       /* public function photoUpload()
        {
            $newName = $this->renameFile($this-> file-> getClientOriginalName());
    
            $this-> photo = $newName;

            $this-> file->move(__DIR__ . '/../../public/img/restaurant/photo', $newName);
        }
*/

        public function removePhoto()
        {
            if(file_exists(__DIR__ . '/../../public/img/restaurant/photo' . $this-> photo) && $this-> photo != 'default.jpg')
            {
                unlink(__DIR__ . '/../../public/img/restaurant/photo' . $this-> photo);
            }
        }

        public function getIdUser(): ?User
        {
            return $this->id_user;
        }

        public function setIdUser(?User $id_user): self
        {
            $this->id_user = $id_user;

            // set (or unset) the owning side of the relation if necessary
            $newId_restaurant = null === $id_user ? null : $this;
            if ($id_user->getIdRestaurant() !== $newId_restaurant) {
                $id_user->setIdRestaurant($newId_restaurant);
            }

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
                $idMeal->setIdRestaurant($this);
            }

            return $this;
        }

        public function removeIdMeal(Meal $idMeal): self
        {
            if ($this->id_meal->contains($idMeal)) {
                $this->id_meal->removeElement($idMeal);
                // set the owning side to null (unless already changed)
                if ($idMeal->getIdRestaurant() === $this) {
                    $idMeal->setIdRestaurant(null);
                }
            }

            return $this;
        }
    
}
