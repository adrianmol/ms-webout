<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $eshop_order_id = null;

    #[ORM\Column (options:['default'=>0], nullable: true)]
    private ?int $erp_order_id = null;

    #[ORM\Column]
    private ?int $store_id = null;

    #[ORM\Column(length: 128)]
    private ?string $store_name = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $store_url = null;

    #[ORM\Column]
    private ?int $customer_id = null;

    #[ORM\Column]
    private ?int $customer_group_id = null;

    #[ORM\Column(length: 128)]
    private ?string $firstname = null;

    #[ORM\Column(length: 128)]
    private ?string $lastname = null;

    #[ORM\Column(length: 64)]
    private ?string $email = null;

    #[ORM\Column(length: 64)]
    private ?string $telephone = null;

    #[ORM\Column(length: 128)]
    private ?string $payment_firstname = null;

    #[ORM\Column(length: 128)]
    private ?string $payment_lastname = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $payment_company = null;

    #[ORM\Column(length: 128)]
    private ?string $payment_address = null;

    #[ORM\Column(length: 64)]
    private ?string $payment_city = null;

    #[ORM\Column]
    private ?int $payment_postcode = null;

    #[ORM\Column(length: 64)]
    private ?string $payment_country = null;

    #[ORM\Column]
    private ?int $payment_country_id = null;

    #[ORM\Column(length: 64)]
    private ?string $payment_zone = null;

    #[ORM\Column(length: 64)]
    private ?string $payment_method = null;

    #[ORM\Column(length: 64)]
    private ?string $payment_code = null;

    #[ORM\Column(length: 128)]
    private ?string $shipping_firstname = null;

    #[ORM\Column(length: 128)]
    private ?string $shipping_lastname = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $shipping_company = null;

    #[ORM\Column(length: 128)]
    private ?string $shipping_address = null;

    #[ORM\Column(length: 64)]
    private ?string $shipping_city = null;

    #[ORM\Column]
    private ?int $shipping_postcode = null;

    #[ORM\Column(length: 64)]
    private ?string $shipping_country = null;

    #[ORM\Column]
    private ?int $shipping_country_id = null;

    #[ORM\Column(length: 64)]
    private ?string $shipping_zone = null;

    #[ORM\Column(length: 64)]
    private ?string $shipping_method = null;

    #[ORM\Column(length: 64)]
    private ?string $shipping_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column (type:'decimal', precision:15, scale:4)]
    private ?float $total = null;

    #[ORM\Column]
    private ?int $order_status_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_added = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modified = null;

    #[ORM\Column]
    private ?bool $is_invoice_order = null;

    #[ORM\Column(nullable: true)]
    private ?int $vat_number = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $doy = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStoreId(): ?int
    {
        return $this->store_id;
    }

    public function setStoreId(int $store_id): self
    {
        $this->store_id = $store_id;

        return $this;
    }

    public function getStoreName(): ?string
    {
        return $this->store_name;
    }

    public function setStoreName(string $store_name): self
    {
        $this->store_name = $store_name;

        return $this;
    }

    public function getStoreUrl(): ?string
    {
        return $this->store_url;
    }

    public function setStoreUrl(?string $store_url): self
    {
        $this->store_url = $store_url;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPaymentFirstname(): ?string
    {
        return $this->payment_firstname;
    }

    public function setPaymentFirstname(string $payment_firstname): self
    {
        $this->payment_firstname = $payment_firstname;

        return $this;
    }

    public function getPaymentLastname(): ?string
    {
        return $this->payment_lastname;
    }

    public function setPaymentLastname(string $payment_lastname): self
    {
        $this->payment_lastname = $payment_lastname;

        return $this;
    }

    public function getPaymentAddress(): ?string
    {
        return $this->payment_address;
    }

    public function setPaymentAddress(string $payment_address): self
    {
        $this->payment_address = $payment_address;

        return $this;
    }

    public function getPaymentCity(): ?string
    {
        return $this->payment_city;
    }

    public function setPaymentCity(string $payment_city): self
    {
        $this->payment_city = $payment_city;

        return $this;
    }

    public function getPaymentPostcode(): ?int
    {
        return $this->payment_postcode;
    }

    public function setPaymentPostcode(int $payment_postcode): self
    {
        $this->payment_postcode = $payment_postcode;

        return $this;
    }

    public function getPaymentCountry(): ?string
    {
        return $this->payment_country;
    }

    public function setPaymentCountry(string $payment_country): self
    {
        $this->payment_country = $payment_country;

        return $this;
    }

    public function getPaymentCountryId(): ?int
    {
        return $this->payment_country_id;
    }

    public function setPaymentCountryId(int $payment_country_id): self
    {
        $this->payment_country_id = $payment_country_id;

        return $this;
    }

    public function getPaymentZone(): ?string
    {
        return $this->payment_zone;
    }

    public function setPaymentZone(string $payment_zone): self
    {
        $this->payment_zone = $payment_zone;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getPaymentCode(): ?string
    {
        return $this->payment_code;
    }

    public function setPaymentCode(string $payment_code): self
    {
        $this->payment_code = $payment_code;

        return $this;
    }

    public function getShippingFirstname(): ?string
    {
        return $this->shipping_firstname;
    }

    public function setShippingFirstname(string $shipping_firstname): self
    {
        $this->shipping_firstname = $shipping_firstname;

        return $this;
    }

    public function getShippingLastname(): ?string
    {
        return $this->shipping_lastname;
    }

    public function setShippingLastname(string $shipping_lastname): self
    {
        $this->shipping_lastname = $shipping_lastname;

        return $this;
    }

    public function getShippingCompany(): ?string
    {
        return $this->shipping_company;
    }

    public function setShippingCompany(?string $shipping_company): self
    {
        $this->shipping_company = $shipping_company;

        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shipping_address;
    }

    public function setShippingAddress(string $shipping_address): self
    {
        $this->shipping_address = $shipping_address;

        return $this;
    }

    public function getShippingCity(): ?string
    {
        return $this->shipping_city;
    }

    public function setShippingCity(string $shipping_city): self
    {
        $this->shipping_city = $shipping_city;

        return $this;
    }

    public function getShippingPostcode(): ?string
    {
        return $this->shipping_postcode;
    }

    public function setShippingPostcode(string $shipping_postcode): self
    {
        $this->shipping_postcode = $shipping_postcode;

        return $this;
    }

    public function getShippingCountry(): ?string
    {
        return $this->shipping_country;
    }

    public function setShippingCountry(string $shipping_country): self
    {
        $this->shipping_country = $shipping_country;

        return $this;
    }

    public function getShippingCountryId(): ?int
    {
        return $this->shipping_country_id;
    }

    public function setShippingCountryId(int $shipping_country_id): self
    {
        $this->shipping_country_id = $shipping_country_id;

        return $this;
    }

    public function getShippingZone(): ?string
    {
        return $this->shipping_zone;
    }

    public function setShippingZone(string $shipping_zone): self
    {
        $this->shipping_zone = $shipping_zone;

        return $this;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shipping_method;
    }

    public function setShippingMethod(string $shipping_method): self
    {
        $this->shipping_method = $shipping_method;

        return $this;
    }

    public function getShippingCode(): ?string
    {
        return $this->shipping_code;
    }

    public function setShippingCode(string $shipping_code): self
    {
        $this->shipping_code = $shipping_code;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getOrderStatusId(): ?int
    {
        return $this->order_status_id;
    }

    public function setOrderStatusId(int $order_status_id): self
    {
        $this->order_status_id = $order_status_id;

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

    public function getPaymentCompany(): ?string
    {
        return $this->payment_company;
    }

    public function setPaymentCompany(string $payment_company): self
    {
        $this->payment_company = $payment_company;

        return $this;
    }

    public function getEshopOrderId(): ?int
    {
        return $this->eshop_order_id;
    }

    public function setEshopOrderId(int $eshop_order_id): self
    {
        $this->eshop_order_id = $eshop_order_id;

        return $this;
    }

    public function getErpOrderId(): ?int
    {
        return $this->erp_order_id;
    }

    public function setErpOrderId(int $erp_order_id): self
    {
        $this->erp_order_id = $erp_order_id;

        return $this;
    }

    public function getFirstnameAndLastname(): ?string
    {
        $name = $this->getFirstname() .' ' . $this->getLastName();

        return $name;
    }

    public function isIsInvoiceOrder(): ?bool
    {
        return $this->is_invoice_order;
    }

    public function setIsInvoiceOrder(bool $is_invoice_order): self
    {
        $this->is_invoice_order = $is_invoice_order;

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

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getDoy(): ?string
    {
        return $this->doy;
    }

    public function setDoy(?string $doy): self
    {
        $this->doy = $doy;

        return $this;
    }
}
