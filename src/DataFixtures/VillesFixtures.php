<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class VillesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $villes = [
            1 => [
                'name' => 'Paris',
            ],
            2 => [
                'name' => 'Marseille',
            ],
            3 => [
                'name' => 'Lion',
            ],
            4 => [
                'name' => 'Toulouse',
            ],
            5 => [
                'name' => 'Nantes',
            ],
            6 => [
                'name' => 'Lille',
            ],

        ];

        foreach($villes as $key => $value){

            //$user = $this->getReference('user_'. $faker->numberBetween(1, 100));

            $ville = new Ville();
            $ville->setName($value['name']);
            $ville->setSlug(strtolower($value['name']));
            $ville->setHexColor($faker->hexColor());
            $manager->persist($ville);

            // Enregistre la catégorie dans une référence
            $this->addReference('ville_' . $key, $ville);
        }

        $manager->flush();
    }
}