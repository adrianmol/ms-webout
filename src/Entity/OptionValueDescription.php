<?php

namespace App\Entity;

use App\Repository\OptionValueDescriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionValueDescriptionRepository::class)]
class OptionValueDescription
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $option_value_id = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $language_id = null;

    #[ORM\Column]
    private ?int $option_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->option_value_id;
    }

    public function setId(int $option_value_id): self
    {
        $this->option_value_id = $option_value_id;
        return $this;
    }

    public function getLanguageId(): ?int
    {
        return $this->language_id;
    }

    public function setLanguageId(int $language_id): self
    {
        $this->language_id = $language_id;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
