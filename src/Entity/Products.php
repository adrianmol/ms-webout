<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $product_id = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(length: 128, nullable: true )]
    private ?string $mpn = null;

    #[ORM\Column (options:['default'=>0])]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $manufacturer_id = null;

    #[ORM\Column(type:'decimal', precision:15, scale:4, nullable: true )]
    private ?float $wholesale_price = null;

    #[ORM\Column(type:'decimal', precision:15, scale:4)]
    private ?float $price = null;
    
    #[ORM\Column(type:'decimal', precision:15, scale:4)]
    private ?float $price_with_vat = null;

    #[ORM\Column (type:'decimal', precision:2, scale:2)]
    private ?float $vat_perc = null;

    #[ORM\Column (type:'decimal', precision:15, scale:4, options:['default'=>0.0000])]
    private ?float $weight = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_added = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modified = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductDescription::class, orphanRemoval: true)]
    private Collection $productDescriptions;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'products')]
    private Collection $category;

    public function __construct()
    {
        $this->productDescriptions = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getMpn(): ?string
    {
        return $this->mpn;
    }

    public function setMpn(?string $mpn): self
    {
        $this->mpn = $mpn;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getManufacturerId(): ?int
    {
        return $this->manufacturer_id;
    }

    public function setManufacturerId(int $manufacturer_id): self
    {
        $this->manufacturer_id = $manufacturer_id;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getWholesalePrice(): ?float
    {
        return $this->wholesale_price;
    }

    public function setWholesalePrice(float $wholesale_price): self
    {
        $this->wholesale_price = $wholesale_price;

        return $this;
    }

    public function getVatPerc(): ?float
    {
        return $this->vat_perc;
    }

    public function setVatPerc(float $vat_perc): self
    {
        $this->vat_perc = $vat_perc;

        return $this;
    }

    public function getPriceWithVat(): ?float
    {
        return $this->price_with_vat;
    }

    public function setPriceWithVat(float $price_with_vat): self
    {
        $this->price_with_vat = $price_with_vat;

        return $this;
    }

    /**
     * @return Collection<int, ProductDescription>
     */
    public function getProductDescriptions(): Collection
    {
        return $this->productDescriptions;
    }

    public function addProductDescription(ProductDescription $productDescription): self
    {
        if (!$this->productDescriptions->contains($productDescription)) {
            $this->productDescriptions->add($productDescription);
            $productDescription->setProduct($this);
        }

        return $this;
    }

    public function removeProductDescription(ProductDescription $productDescription): self
    {
        if ($this->productDescriptions->removeElement($productDescription)) {
            // set the owning side to null (unless already changed)
            if ($productDescription->getProduct() === $this) {
                $productDescription->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, categories>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(categories $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(categories $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
