<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $category_id = null;

    #[ORM\Column]
    private ?int $parent_id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $category_code = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $eshop_status = null;

    #[ORM\Column]
    private ?int $order_sort = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_added = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modified = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CategoryDescription::class)]
    private Collection $category_description;

    #[ORM\ManyToMany(targetEntity: Products::class, mappedBy: 'category')]
    private Collection $products;

    public function __construct()
    {
        $this->category_description = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(int $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    public function getCategoryCode(): ?string
    {
        return $this->category_code;
    }

    public function getCategoryName(): ?string
    {
        $category = $this->getCategoryDescription()->get(0);

        return $category->getName();
    }

    public function setCategoryCode(?string $category_code): self
    {
        $this->category_code = $category_code;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEshopStatus(): ?int
    {
        return $this->eshop_status;
    }

    public function setEshopStatus(int $eshop_status): self
    {
        $this->eshop_status = $eshop_status;

        return $this;
    }

    public function getOrderSort(): ?int
    {
        return $this->order_sort;
    }

    public function setOrderSort(int $order_sort): self
    {
        $this->order_sort = $order_sort;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->date_added;
    }

    public function setDateAdded(\DateTimeInterface $date_added): self
    {
        $this->date_added = $date_added;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(\DateTimeInterface $date_modified): self
    {
        $this->date_modified = $date_modified;

        return $this;
    }

    /**
     * @return Collection<int, CategoryDescription>
     */
    public function getCategoryDescription(): Collection
    {
        return $this->category_description;
    }

    public function addCategoryDescription(CategoryDescription $categoryDescription): self
    {
        if (!$this->category_description->contains($categoryDescription)) {
            $this->category_description->add($categoryDescription);
            $categoryDescription->setCategories($this);
        }

        return $this;
    }

    public function removeCategoryDescription(CategoryDescription $categoryDescription): self
    {
        if ($this->category_description->removeElement($categoryDescription)) {
            // set the owning side to null (unless already changed)
            if ($categoryDescription->getCategories() === $this) {
                $categoryDescription->setCategories(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }
}
