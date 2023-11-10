<?php


namespace App\Api;


use App\Entity\Equipement;
use App\Repository\EquipementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\SerializerInterface;

class ApiUpdateEquipment extends AbstractController
{
    /**
     * @throws \ReflectionException
     */
    public function __invoke($id, Request $request, SerializerInterface $serializer, EquipementRepository $equipementRepository): JsonResponse
    {
        $equipmentDataFromClient = $request->toArray();
        $equipement = $equipementRepository->findOneBy(['id' => $id, 'deleted' => false]);

        if($equipement){
            $equipement = $this->buildDataAccordingToClientData($equipmentDataFromClient, $equipement);

            $equipementRepository->add($equipement, true);
        }else{
            return new JsonResponse([
                'message' => ''
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'message' => ''
        ], Response::HTTP_OK);
    }

    /**
     * @throws \ReflectionException
     */
    function buildDataAccordingToClientData($clientData, Equipement $entity)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($clientData as $property => $value) {
            $propertyAccessor->setValue($entity, $property, $value);
        }

        return $entity;
    }
}