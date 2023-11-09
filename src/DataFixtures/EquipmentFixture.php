<?php

namespace App\DataFixtures;

use App\Entity\Equipement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EquipmentFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $equipement = new Equipement();
        $equipement->setName('IPhone X 128Gb');
        $equipement->setCategory('Phone');
        $equipement->setNumber('IPX-1234');
        $equipement->setDescription('Fast smartphone with powerfull chip');
        $manager->persist($equipement);

        $equipement1 = new Equipement();
        $equipement1->setName('MSI Katana');
        $equipement1->setCategory('PC');
        $equipement1->setNumber('MSIK-1234');
        $equipement1->setDescription('');
        $manager->persist($equipement1);

        $equipement2 = new Equipement();
        $equipement2->setName('Mouse');
        $equipement2->setCategory('PC');
        $equipement2->setNumber('MS-1234');
        $equipement2->setDescription('Wireless Mouse');
        $manager->persist($equipement2);

        $equipement3 = new Equipement();
        $equipement3->setName('Macbook');
        $equipement3->setCategory('PC');
        $equipement3->setNumber('MC-1234');
        $equipement3->setDescription('Fast macbook with powerfull chip');
        $manager->persist($equipement3);

        $equipement4 = new Equipement();
        $equipement4->setName('HDD');
        $equipement4->setCategory('PC');
        $equipement4->setNumber('HDD-500');
        $equipement4->setDescription('');
        $manager->persist($equipement4);

        $manager->flush();
    }
}
