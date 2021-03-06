<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

  
    /**
     * @ORM\Column(type="string", length=20) 
     */
    private $role;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo= 'default.png';
    // Par défaut, si on ne met pas d'image, on ira chercher cette l'image 'default.jpg'.

    private $file;
    //Cette propieté va correspondre au fichier uploader dans le formulaire donc as besoin de la mapper.

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $wallet = '50';

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=Restaurant::class, inversedBy="id_user", cascade={"persist", "remove"})
     */
    private $id_restaurant;

    /**
     * @ORM\OneToMany(targetEntity=Command::class, mappedBy="id_user")
     */
    private $id_command;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="user_id")
     */
    private $rate_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postal;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Firstname;

    public function __construct()
    {
        $this->id_command = new ArrayCollection();
        $this->rate_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

      /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this -> role];
    }

    public function getRole(): array
    {
        return $this -> role;
    }


    public function setRole(string $role): self
    {
        $this->role = $role;

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
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
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
        $newName = $this->renameFile($this-> file-> getClientOriginalName());

        $this-> photo = $newName;

        $this-> file->move(__DIR__ . '/../../public/img/users/', $newName);

    }

    public function renameFile($name)
    {
        return 'fichier_' . time() . '_' . rand(1, 9999) . '_' . $name;
    }

    public function removeFile()
    {
        if(file_exists(__DIR__ . '/../../public/img/users/' . $this-> photo) && $this-> photo != 'default.jpg')
        {
            unlink(__DIR__ . '/../../public/img/users/' . $this-> photo);
        }
    }

    public function getWallet(): ?string
    {
        return $this->wallet;
    }

    public function setWallet(string $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    /**
     * @return Collection|Command[]
     */
    public function getIdCommand(): Collection
    {
        return $this->id_command;
    }

    public function addIdCommand(Command $idCommand): self
    {
        if (!$this->id_command->contains($idCommand)) {
            $this->id_command[] = $idCommand;
            $idCommand->setIdCommand($this);
        }

        return $this;
    }

    public function removeIdCommand(Command $idCommand): self
    {
        if ($this->id_command->contains($idCommand)) {
            $this->id_command->removeElement($idCommand);
            // set the owning side to null (unless already changed)
            if ($idCommand->getIdCommand() === $this) {
                $idCommand->setIdCommand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rate[]
     */
    public function getRateId(): Collection
    {
        return $this->rate_id;
    }

    public function addRateId(Rate $rateId): self
    {
        if (!$this->rate_id->contains($rateId)) {
            $this->rate_id[] = $rateId;
            $rateId->setUserId($this);
        }

        return $this;
    }

    public function removeRateId(Rate $rateId): self
    {
        if ($this->rate_id->contains($rateId)) {
            $this->rate_id->removeElement($rateId);
            // set the owning side to null (unless already changed)
            if ($rateId->getUserId() === $this) {
                $rateId->setUserId(null);
            }
        }

        return $this;
    }


        /** @see \Serializable::serialize() */
        public function serialize()
        {
            return serialize(array(
                $this->id,
                $this->username,
                $this->password,
                // see section on salt below
                // $this->salt,
            ));
        }
    
        /** @see \Serializable::unserialize() */
        public function unserialize($serialized)
        {
            list (
                $this->id,
                $this->username,
                $this->password,
                // see section on salt below
                // $this->salt
            ) = unserialize($serialized);
        }

        public function getPostal(): ?string
        {
            return $this->postal;
        }

        public function setPostal(?string $postal): self
        {
            $this->postal = $postal;

            return $this;
        }

        public function getZipcode(): ?string
        {
            return $this->zipcode;
        }

        public function setZipcode(?string $zipcode): self
        {
            $this->zipcode = $zipcode;

            return $this;
        }

        public function getName(): ?string
        {
            return $this->Name;
        }

        public function setName(?string $Name): self
        {
            $this->Name = $Name;

            return $this;
        }

        public function getFirstname(): ?string
        {
            return $this->Firstname;
        }

        public function setFirstname(?string $Firstname): self
        {
            $this->Firstname = $Firstname;

            return $this;
        }
}
