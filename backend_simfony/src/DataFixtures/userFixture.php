<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class userFixture extends Fixture
{
    public  function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        
            $user = new User();
                $user->setUsername('admin')
                ->setEmail('admin@gmail.com')
                ->setVerified(true)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    'admin'
                ))
                ->setApiToken('admin');
            
            $manager->persist($user);

            $user = new User();
                $user->setUsername('super_admin')
                ->setEmail('super_admin@gmail.com')
                ->setVerified(true)
                ->setRoles(['ROLE_SUPER_ADMIN'])
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    'super_admin'
                ))
                ->setApiToken('super_admin');
            
            $manager->persist($user);
    

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setUsername($faker->userName())
            ->setEmail($faker->email())
            ->setVerified(true)
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                'poney'
            ))
            ->setApiToken($faker->word().'_'.$i);
        
        $this->addReference('user_'.$i, $user);
        $manager->persist($user);
        }

        $manager->flush();
    }
}
