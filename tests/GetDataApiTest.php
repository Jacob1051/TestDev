<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Equipement;

class GetDataApiTest extends ApiTestCase
{
    public function testGetEquipementApi(): void
    {
        $response = static::createClient()->request('GET', '/api/equipements');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(Equipement::class); 
    }
}
