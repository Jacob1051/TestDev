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
        try {
            $equipementRepository->softDeleteById($id);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => 'An error occured: '.$exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse([
            'message' => ''
        ], Response::HTTP_NO_CONTENT);
    }
}