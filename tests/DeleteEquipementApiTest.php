<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Equipement;

class DeleteEquipementApiTest extends ApiTestCase
{
    public function testDeleteEquipement(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/equipements/1');

        $this->assertResponseStatusCodeSame(204);

        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Equipement::class)->findOneBy(['id' => '1', 'deleted' => false])
        );
    }

    public function testDeleteEquipementThatWasDeleted(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/equipements/1');

        $this->assertResponseStatusCodeSame(404);
    }
}
