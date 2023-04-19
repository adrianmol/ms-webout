<?php

namespace App\Entity;

use App\Repository\ProductVariationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductVariationsRepository::class)]
class ProductVariations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $product_master_id = null;

    #[ORM\Column]
    private ?int $variation_id = null;

    #[ORM\Column]
    private ?int $option_id = null;

    #[ORM\Column]
    private ?int $option_value_id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $barcode = null;

    #[ORM\Column(type: Types::SMALLINT,options:['default'=>0])]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mpn = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductMasterId(): ?int
    {
        return $this->product_master_id;
    }

    public function setProductMasterId(int $product_master_id): self
    {
        $this->product_master_id = $product_master_id;

        return $this;
    }

    public function getVariationId(): ?int
    {
        return $this->variation_id;
    }

    public function setVariationId(int $variation_id): self
    {
        $this->variation_id = $variation_id;

        return $this;
    }

    public function getOptionId(): ?int
    {
        return $this->option_id;
    }

    public function setOptionId(int $option_id): self
    {
        $this->option_id = $option_id;

        return $this;
    }

    public function getOptionValueId(): ?int
    {
        return $this->option_value_id;
    }

    public function setOptionValueId(int $option_value_id): self
    {
        $this->option_value_id = $option_value_id;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

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

    public function setMpn(string $mpn): self
    {
        $this->mpn = $mpn;

        return $this;
    }
}
