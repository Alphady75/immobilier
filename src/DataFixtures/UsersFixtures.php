<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbUsers = 1; $nbUsers <= 5; $nbUsers++){
            $user = new User();
            if($nbUsers === 1){
                $user->setEmail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMIN']);
                $user->setCompte('admin');
            }else{
                $user->setRoles(['ROLE_USER']);
                $user->setCompte('user');
                $user->setEmail($faker->email);
            }
            $user->setPrenom($faker->firstName());
            $user->setGenre('Homme');
            $user->setNom($faker->lastName());
            $user->setBiographie($faker->realText(150));
            //$user->setIsVerified(true);
            //$user->setIsLocked(false);
            $user->setPassword($this->encoder->hashPassword($user, 'azerty'));
            $manager->persist($user);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('user_'. $nbUsers, $user);
        }

        $manager->flush();
    }
}
