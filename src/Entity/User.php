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
class User implements UserInterface
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
    private $role = 'ROLE_USER';

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
     * @ORM\OneToMany(targetEntity=Command::class, mappedBy="id_command")
     */
    private $id_command;

    public function __construct()
    {
        $this->id_command = new ArrayCollection();
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
    public function getRoles(): string
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(string $roles): self
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

    public function getWallaet(): ?string
    {
        return $this->wallaet;
    }

    public function setWallaet(string $wallaet): self
    {
        $this->wallaet = $wallaet;

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
}
