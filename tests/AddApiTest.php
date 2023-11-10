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
    public function testAddValidEquipement(): void
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
        $this->assertMatchesRegularExpression('~^/api/equipements/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Equipement::class);
    }

    public function testAddEquipementWithExtraProperty(): void
    {
        $response = static::createClient()->request('POST', '/api/equipements', ['json' => [
            "name" => "PC Portable Gamer",
            "category" => "Computer",
            "number" => "PC-4090",
            "description" => "Super strong gaming laptop",
            "customProperty" => "testValue"
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            "@context" => "/api/contexts/Equipement",
            "@type" => "Equipement",
            "name" => "PC Portable Gamer",
            "category" => "Computer",
            "number" => "PC-4090",
            "description" => "Super strong gaming laptop",
        ]);
        $this->assertMatchesRegularExpression('~^/api/equipements/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Equipement::class);
    }

    public function testAddEquipementWithMissingNameAndNumberProperty(): void
    {
        $response = static::createClient()->request('POST', '/api/equipements', ['json' => [
            "category" => "Computer",
            "description" => ""
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: This value should not be blank.
number: This value should not be blank.',
        ]);
    }
}
