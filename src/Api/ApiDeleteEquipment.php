<?php


namespace App\Api;

use App\Repository\EquipementRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ApiDeleteEquipment extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __invoke(string $id, EquipementRepository $equipementRepository): JsonResponse
    {
        $equipement = $equipementRepository->findOneBy(['id'=> $id, 'deleted'=> false]);

        if($equipement){
            $equipementRepository->softDeleteById($id);
        }else{
            return new JsonResponse([
                'message' => ''
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'message' => ''
        ], Response::HTTP_NO_CONTENT);
    }
}