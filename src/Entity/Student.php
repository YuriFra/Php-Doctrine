<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email;

    /** @Embedded(class = "Address") */
    private ?Address $address;

    /**
     * Many students have one teacher.
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher", inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Teacher $teacher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTeacher() : ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): void
    {
        $this->teacher = $teacher;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'address' => $this->getAddress()->toArray(),
            'email' => $this->getEmail(),
            'teacher_id'=> $this->getTeacher()->getId()
        ];
    }
}
