<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Equipement;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AddApiTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testAddEquipement(): void
    {
        $response = static::createClient()->request('POST', '/api/equipements', ['json' => [
            "name" => "PC Portable",
            "category" => "Computer",
            "number" => "PC-4321",
            "description" => ""
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            "@context" => "/api/contexts/Equipement",
            "@type" => "Equipement",
            "name" => "PC Portable",
            "category" => "Computer",
            "number" => "PC-4321",
            "description" => ""
        ]);
        $this->assertMatchesRegularExpression('~^/books/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Equipement::class);
    }

    public function testAddInvalidEquipement(): void
    {
        $response = static::createClient()->request('POST', '/api/equipements', ['json' => [
            "name" => "PC Portable",
            "category" => "Computer",
            "description" => ""
        ]]);

        $this->assertResponseStatusCodeSame(500);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
