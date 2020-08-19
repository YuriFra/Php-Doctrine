<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $last_name;

    /** @Embedded(class = "Address") */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $email;

    /**
     * One teacher has many students.
     * @OneToMany(targetEntity="App\Entity\Student", mappedBy="teacher")
     * @ORM\JoinColumn(nullable=false)
     */
    private ArrayCollection $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getStudents(): ArrayCollection
    {
        return $this->students;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): string
    {
        return $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): string
    {
        return $this->last_name = $last_name;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'address' => $this->getAddress()->toArray(),
            'email' => $this->getEmail(),
            'students'=> $this->getStudents()
        ];
    }
}
