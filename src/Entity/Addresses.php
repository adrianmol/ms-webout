<?php

namespace App\Entity;

use App\Repository\AddressesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressesRepository::class)]
class Addresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?customers $customer = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $address_number = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $post_code = null;

    #[ORM\Column]
    private ?int $country_id = null;

    #[ORM\Column(length: 255)]
    private ?string $country_name = null;

    #[ORM\Column(length: 255)]
    private ?string $region_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->first_name;
    }

    public function setFirstname(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->last_name;
    }

    public function setLastname(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

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

    public function getAddressNumber(): ?int
    {
        return $this->address_number;
    }

    public function setAddressNumber(int $address_number): self
    {
        $this->address_number = $address_number;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    public function setPostCode(string $post_code): self
    {
        $this->post_code = $post_code;

        return $this;
    }

    public function getCountryId(): ?int
    {
        return $this->country_id;
    }

    public function setCountryId(int $country_id): self
    {
        $this->country_id = $country_id;

        return $this;
    }

    public function getCustomerId(): ?customers
    {
        return $this->customer;
    }

    public function setCustomerId(?customers $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->country_name;
    }

    public function setCountryName(string $country_name): self
    {
        $this->country_name = $country_name;

        return $this;
    }

    public function getRegionName(): ?string
    {
        return $this->region_name;
    }

    public function setRegionName(string $region_name): self
    {
        $this->region_name = $region_name;

        return $this;
    }
}
