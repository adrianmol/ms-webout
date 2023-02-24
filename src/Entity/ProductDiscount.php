<?php

namespace App\Entity;

use App\Repository\ProductDiscountRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ProductDiscountRepository::class)]
class ProductDiscount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $discount_id = null;

    #[ORM\ManyToOne(inversedBy: 'productDiscounts')]
    private ?Products $product = null;

    #[ORM\Column (options:['default'=>0], nullable: true)]
    private ?int $customer_group_id = null;

    #[ORM\Column (options:['default'=>0], nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(type:'decimal', precision:15, scale:4, nullable: true )]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column(length: 255, nullable: true, options:['default'=>'product_discount'])]
    private ?string $discount_code = null;

    public function getId(): ?int
    {
        return $this->discount_id;
    }

    public function getCustomerGroupId(): ?int
    {
        return $this->customer_group_id;
    }

    public function setCustomerGroupId(int $customer_group_id): self
    {
        $this->customer_group_id = $customer_group_id;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price, float $percentage): self
    {

        $this->price = $price - ($price * $percentage / 100);

        return $this;
    }

    public function getDateStart(): ?\DateTimeImmutable
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTimeImmutable $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeImmutable
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeImmutable $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getDiscountCode(): ?string
    {
        return $this->discount_code;
    }

    public function setDiscountCode(?string $discount_code): self
    {
        $this->discount_code = $discount_code;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }
}
