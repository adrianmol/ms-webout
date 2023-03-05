<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customer_group')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomerGroup $customer_group = null;
    
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Addresses::class)]
    private Collection $addresses;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 24)]
    private ?string $phone = null;

    #[ORM\Column(length: 1, options:['default'=>1])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $vat_number = null;

    #[ORM\Column(length: 255)]
    private ?string $doy = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Addresses>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Addresses $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setCustomerId($this);
        }

        return $this;
    }

    public function removeAddress(Addresses $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCustomerId() === $this) {
                $address->setCustomerId(null);
            }
        }

        return $this;
    }

    public function getCustomerGroup(): ?CustomerGroup
    {
        return $this->customer_group;
    }

    public function setCustomerGroup(?CustomerGroup $customer_group): self
    {
        $this->customer_group = $customer_group;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getVatNumber(): ?int
    {
        return $this->vat_number;
    }

    public function setVatNumber(?int $vat_number): self
    {
        $this->vat_number = $vat_number;

        return $this;
    }

    public function getDoy(): ?string
    {
        return $this->doy;
    }

    public function setDoy(string $doy): self
    {
        $this->doy = $doy;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }
}
