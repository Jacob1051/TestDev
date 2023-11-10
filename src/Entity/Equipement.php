<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Api\ApiDeleteEquipment;
use App\Api\ApiGetAllEquipment;
use App\Api\ApiUpdateEquipment;
use App\Repository\EquipementRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EquipementRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    collectionOperations: [
        'get_all_equipment' => [
            'method'     => 'GET',
            'path'       => 'equipements',
            'controller' => ApiGetAllEquipment::class,
            'read'       => false,
        ],
        'create_equipment'  => [
            'method'          => 'POST',
            'read'            => false,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type'       => 'object',
                                'properties' => [
                                    'name'        => ['type' => 'string'],
                                    'category'    => ['type' => 'string'],
                                    'number'      => ['type' => 'string'],
                                    'description' => ['type' => 'string'],
                                ],
                                'required'   => [
                                    'name',
                                    'category',
                                    'number',
                                    'description',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations: [
        'GET',
        'delete_equipment' => [
            'method'       => 'DELETE',
            'path'         => 'equipements/{id}',
            'controller'   => ApiDeleteEquipment::class,
            'read'         => false,
            'requirements' => ['id' => '\d+'],
        ],
        'update_equipment' => [
            'method'          => 'PATCH',
            'path'            => 'equipements/{id}',
            'controller'      => ApiUpdateEquipment::class,
            'read'            => false,
            'openapi_context' => [
                'summary'     => 'Update Equipment',
                'description' => 'Update Equipment',
            ],
        ],

    ],
    denormalizationContext: ['groups' => ['equipment:write']],
    normalizationContext: ['groups' => ['equipment:read']],
    paginationPartial: true,
    paginationViaCursor: [
        [
            'field'     => 'id',
            'direction' => 'ASC',
        ],
    ],
)]
#[ApiFilter(OrderFilter::class, properties: ['id' => 'ASC'])]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'id'       => 'exact',
        'name'     => 'partial',
        'category' => 'partial',
        'number'   => 'exact',
    ]
)]
class Equipement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['equipment:read', 'equipment:write'])]
    #[Assert\NotBlank]
    #[Assert\NotNull]

    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['equipment:read', 'equipment:write'])]

    private ?string $category = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 255)]
    #[Groups(['equipment:read', 'equipment:write'])]

    private ?string $number = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['equipment:read', 'equipment:write'])]

    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['equipment:read'])]

    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['equipment:read'])]

    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column]

    private ?bool $deleted = null;


    public function __construct()
    {
        $this->description = '';
        $this->deleted     = false;

    }//end __construct()


    public function getId(): ?int
    {
        return $this->id;

    }//end getId()


    public function getName(): ?string
    {
        return $this->name;

    }//end getName()


    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;

    }//end setName()


    public function getCategory(): ?string
    {
        return $this->category;

    }//end getCategory()


    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;

    }//end setCategory()


    public function getNumber(): ?string
    {
        return $this->number;

    }//end getNumber()


    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;

    }//end setNumber()


    public function getDescription(): ?string
    {
        return $this->description;

    }//end getDescription()


    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;

    }//end setDescription()


    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;

    }//end getCreatedAt()


    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;

    }//end setCreatedAt()


    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;

    }//end getUpdatedAt()


    public function setUpdatedAt(?DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;

    }//end setUpdatedAt()


    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTime('now');
        }

    }//end updatedTimestamps()


    public function isDeleted(): ?bool
    {
        return $this->deleted;

    }//end isDeleted()


    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;

    }//end setDeleted()


    #[ORM\PreUpdate]
    public function updateMe()
    {
        $this->updatedAt = new DateTime('now');

    }//end updateMe()


}//end class
