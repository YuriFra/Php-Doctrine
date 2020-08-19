<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Address
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * Address constructor.
     * @param $street
     * @param $streetNumber
     * @param $zipcode
     * @param $city
     */
    public function __construct($street, $streetNumber, $zipcode, $city)
    {
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->zipcode = $zipcode;
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    /**
     * @param mixed $streetNumber
     */
    public function setStreetNumber(string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @return mixed
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @param mixed $zipcode
     */
    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return mixed
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity(string $city): self
    {
        return $this->city = $city;
    }

    public function toArray(): array
    {
        return [
            'street' => $this->getStreet(),
            'streetNumber' => $this->getStreetNumber(),
            'city' => $this->getCity(),
            'zipcode' => $this->getZipcode()
        ];
    }
}
