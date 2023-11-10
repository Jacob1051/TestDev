<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Equipement;

class UpdateEquipementApiTest extends ApiTestCase
{
    public function testUpdateWithValidEquipementAndTheEquipementsNameAndCategoryShouldBeUpdatedAndTheOtherPropertiesNot(): void
    {
        $oldEquipement = static::getContainer()->get('doctrine')->getRepository(Equipement::class)->findOneBy(['id' => '2']);

        $response = static::createClient()->request('PATCH', '/api/equipements/2', [
            'json' => [
                "name" => "MSI Katana Modified",
                "category" => "PC Modified",
            ],
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/merge-patch+json'
            ]
        ]);

        $newEquipement = static::getContainer()->get('doctrine')->getRepository(Equipement::class)->findOneBy(['id' => '2']);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertSame("MSI Katana Modified", $newEquipement->getName());
        $this->assertSame("PC Modified", $newEquipement->getCategory());

        $this->assertSame($oldEquipement->getNumber(), $newEquipement->getNumber());
        $this->assertSame($oldEquipement->getDescription(), $newEquipement->getDescription());

        $this->assertSame((new \DateTime('now'))->format('Y-m-d'), $newEquipement->getUpdatedAt()->format('Y-m-d'));
    }

    public function testUpdateWithNonExistentEquipementAndTheEquipementShouldNotBeUpdated(): void
    {
        $oldEquipement = static::getContainer()->get('doctrine')->getRepository(Equipement::class)->findOneBy(['id' => '2']);

        $response = static::createClient()->request('PATCH', '/api/equipements/20', [
            'json' => [
                "name" => "MSI Katana Modified",
                "category" => "PC Modified",
            ],
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/merge-patch+json'
            ]
        ]);

        $newEquipement = static::getContainer()->get('doctrine')->getRepository(Equipement::class)->findOneBy(['id' => '2']);

        $this->assertResponseStatusCodeSame(404);

        $this->assertSame($oldEquipement->getName(), $newEquipement->getName());
        $this->assertSame($oldEquipement->getCategory(), $newEquipement->getCategory());
        $this->assertSame($oldEquipement->getNumber(), $newEquipement->getNumber());
        $this->assertSame($oldEquipement->getDescription(), $newEquipement->getDescription());
    }

    public function testUpdateWithFullUpdateOfTheEntityEquipement(): void
    {
        $idToBeTested = 3;
        $response = static::createClient()->request('PATCH', '/api/equipements/'.$idToBeTested, [
            'json' => [
                "name" => "Mouse Modified",
                "category" => "PC Modified",
                "number" => "MS-1234 Modified",
                "description" => "Wireless Mouse Modified",
            ],
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/merge-patch+json'
            ]
        ]);

        $newEquipement = static::getContainer()->get('doctrine')->getRepository(Equipement::class)->findOneBy(['id' => $idToBeTested]);

        $this->assertResponseStatusCodeSame(200);

        $this->assertSame("Mouse Modified", $newEquipement->getName());
        $this->assertSame("PC Modified", $newEquipement->getCategory());
        $this->assertSame("MS-1234 Modified", $newEquipement->getNumber());
        $this->assertSame("Wireless Mouse Modified", $newEquipement->getDescription());

        $this->assertSame((new \DateTime('now'))->format('Y-m-d'), $newEquipement->getUpdatedAt()->format('Y-m-d'));
    }
}
