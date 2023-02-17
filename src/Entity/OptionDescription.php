<?php

namespace App\Entity;

use App\Repository\OptionDescriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionDescriptionRepository::class)]
class OptionDescription
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $option_id = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $language_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->option_id;
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
