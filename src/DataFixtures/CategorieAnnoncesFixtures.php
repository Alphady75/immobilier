<?php

namespace App\DataFixtures;

use App\Entity\CategorieAnnonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategorieAnnoncesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $categories = [
            1 => [
                'name' => 'Compagne',
            ],
            2 => [
                'name' => 'Menage',
            ],
            3 => [
                'name' => 'Villa',
            ],

        ];

        foreach($categories as $key => $value){

            //$user = $this->getReference('user_'. $faker->numberBetween(1, 100));

            $categorie = new CategorieAnnonce();
            $categorie->setName($value['name']);
            $categorie->setSlug(strtolower($value['name']));
            $categorie->setHexColor($faker->hexColor());
            $manager->persist($categorie);

            // Enregistre la catégorie dans une référence
            $this->addReference('categorieannonce_' . $key, $categorie);
        }

        $manager->flush();
    }
}
