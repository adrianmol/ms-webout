<?php

namespace App\Entity;

use App\Repository\CustomerGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerGroupRepository::class)]
class CustomerGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $group_code = null;

    #[ORM\OneToMany(mappedBy: 'customer_group', targetEntity: Customers::class)]
    private Collection $customer_group;

    public function __construct()
    {
        $this->customer_group = new ArrayCollection();
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

    public function getGroupCode(): ?string
    {
        return $this->group_code;
    }

    public function setGroupCode(string $group_code): self
    {
        $this->group_code = $group_code;

        return $this;
    }

    /**
     * @return Collection<int, Customers>
     */
    public function getCustomerGroup(): Collection
    {
        return $this->customer_group;
    }

    public function addCustomerGroup(Customers $customerGroup): self
    {
        if (!$this->customer_group->contains($customerGroup)) {
            $this->customer_group->add($customerGroup);
            $customerGroup->setCustomerGroup($this);
        }

        return $this;
    }

    public function removeCustomerGroup(Customers $customerGroup): self
    {
        if ($this->customer_group->removeElement($customerGroup)) {
            // set the owning side to null (unless already changed)
            if ($customerGroup->getCustomerGroup() === $this) {
                $customerGroup->setCustomerGroup(null);
            }
        }

        return $this;
    }
}
