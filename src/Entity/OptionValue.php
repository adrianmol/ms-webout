<?php

namespace App\Entity;

use App\Repository\OptionValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionValueRepository::class)]
class OptionValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $option_value_id = null;

    #[ORM\Column]
    private ?int $option_id = null;

    #[ORM\Column(length: 255)]
    private ?string $sort_order = null;

    public function getId(): ?int
    {
        return $this->option_value_id;
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

    public function getSortOrder(): ?string
    {
        return $this->sort_order;
    }

    public function setSortOrder(string $sort_order): self
    {
        $this->sort_order = $sort_order;

        return $this;
    }
}
