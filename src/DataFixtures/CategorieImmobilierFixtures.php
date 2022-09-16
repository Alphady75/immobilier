<?php

namespace App\DataFixtures;

use App\Entity\CategorieImmobilier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategorieImmobilierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $categories = [
            1 => [
                'name' => 'Appartement',
            ],
            2 => [
                'name' => 'Hotel',
            ],
            3 => [
                'name' => 'Villa',
            ],

        ];

        foreach($categories as $key => $value){

            //$user = $this->getReference('user_'. $faker->numberBetween(1, 100));

            $categorie = new CategorieImmobilier();
            $categorie->setName($value['name']);
            $categorie->setSlug(strtolower($value['name']));
            $categorie->setHexColor($faker->hexColor());
            $manager->persist($categorie);

            // Enregistre la catégorie dans une référence
            $this->addReference('categorieimmobilier_' . $key, $categorie);
        }

        $manager->flush();
    }
}
