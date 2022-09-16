<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class AnnoncesFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager): void
    {
    $faker = Faker\Factory::create('fr_FR');

        for($nbannonces = 1; $nbannonces <= 100; $nbannonces++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 5));
            $categorie = $this->getReference('categorieannonce_'. $faker->numberBetween(1, 3));
            
            $annonce = new Annonce();
            $annonce->setUser($user);
            $annonce->setCategorie($categorie);
            $annonce->setName($faker->realText(100));
            $annonce->setSlug('annonce-' . $nbannonces);
            $annonce->setDescription($faker->realText(200));
            $annonce->setOnline($faker->numberBetween(0, 1));

            /* On uploade et on génère les images
            for($image = 1; $image <= 100; $image++){
                // $img = $faker->image('public/uploads/images/annonces');

                $nomImg = "image.png";
                $imageannonce = new Image();
                $imageannonce->setImageName($nomImg);
                $annonce->addImage($imageannonce);
            }*/

            // Enregistre l'annonce dans une référence
            $this->addReference('annonce_' . $nbannonces, $annonce);
            
            $manager->persist($annonce);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieAnnoncesFixtures::class,
            UsersFixtures::class,
        ];
    }
}
