<?php

namespace App\DataFixtures;

use App\Entity\Immobilier;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class ImmobilierFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager): void
    {
    $faker = Faker\Factory::create('fr_FR');

        for($nbimmobiliers = 1; $nbimmobiliers <= 100; $nbimmobiliers++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 5));
            $categorie = $this->getReference('categorieimmobilier_'. $faker->numberBetween(1, 3));
            $ville = $this->getReference('ville_'. $faker->numberBetween(1, 3));

            $immobilier = new Immobilier();
            $immobilier->setUser($user);
            $immobilier->setCategorie($categorie);
            $immobilier->setName($faker->realText(100));
            $immobilier->setSlug('immobilier-' . $nbimmobiliers);
            $immobilier->setTarif($faker->numberBetween(1000, 1000000));
            $immobilier->setReference($faker->streetAddress());
            $immobilier->setSurfaceMin($faker->numberBetween(15, 500));
            $immobilier->setSurfaceMax($faker->numberBetween(15, 500));
            $immobilier->setType('maison-familliale');
            $immobilier->setDescription($faker->realText(200));
            $immobilier->setStatut('a-louer');
            $immobilier->setOnline($faker->numberBetween(0, 1));
            $immobilier->setVille($ville);

            /* On uploade et on génère les images
            for($image = 1; $image <= 100; $image++){
                // $img = $faker->image('public/uploads/images/immobiliers');

                $nomImg = "image.png";
                $imageimmobilier = new Image();
                $imageimmobilier->setImageName($nomImg);
                $immobilier->addImage($imageimmobilier);
            }*/

            // Enregistre l'immobilier dans une référence
            $this->addReference('immobilier_' . $nbimmobiliers, $immobilier);
            
            $manager->persist($immobilier);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieImmobilierFixtures::class,
            UsersFixtures::class,
            VillesFixtures::class,
        ];
    }
}
