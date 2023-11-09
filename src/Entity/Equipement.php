<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Api\ApiDeleteEquipment;
use App\Api\ApiCreateEquipment;
use App\Api\ApiGetAllEquipment;
use App\Api\ApiUpdateEquipment;
use App\Repository\EquipementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EquipementRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    denormalizationContext: ['groups' => ['equipment:write']],
    normalizationContext: ['groups' => ['equipment:read']],
    collectionOperations: [
        "POST",
        "get_all_equipment" => [
            "method" => "GET",
            "path" => "equipements",
            "controller" => ApiGetAllEquipment::class,
            "read" => false
        ],
    ],
    itemOperations: [
        "GET",
        "delete_equipment" => [
            "method" => "DELETE",
            "path" => "equipements/{id}",
            "controller" => ApiDeleteEquipment::class,
            "read" => false
        ],
        "update_equipment" => [
            "method" => "PATCH",
            "path" => "equipements/{id}",
            "controller" => ApiUpdateEquipment::class,
            "read" => false,
            "openapi_context" => [
                "summary" => "Update Equipment",
                "description" => "Update Equipment",
            ]
        ],

    ],
    paginationPartial: true,
    paginationViaCursor: [
        ['field' => 'id', 'direction' => 'ASC']
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ["id" => "ASC"])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial', 'category' => 'partial', 'number' => 'exact'])]
class Equipement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["equipment:read", "equipment:write"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["equipment:read", "equipment:write"])]
    private ?string $category = null;

    #[ORM\Column(length: 255)]
    #[Groups(["equipment:read", "equipment:write"])]
    private ?string $number = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["equipment:read", "equipment:write"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["equipment:read"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["equipment:read"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    public function __construct()
    {
        $this->description = '';
        $this->deleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if ($this->createdAt == null) {
            $this->createdAt = new \DateTime('now');
        }
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function updateMe()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
