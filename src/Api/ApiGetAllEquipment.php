<?php


namespace App\Api;


use ApiPlatform\Doctrine\Orm\Paginator;
use App\Repository\EquipementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGetAllEquipment extends AbstractController
{
    public function __construct(
        private EquipementRepository $equipementRepository
    )
    {
    }

    public function __invoke(Request $request): Paginator
    {
        return $this->equipementRepository->findAllWithPagination($request);
    }
}