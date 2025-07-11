<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);
        $existingAdmin = $userRepository->findOneBy(['email'=>'admin@granota.com']);
        if($existingAdmin){
            return;
        }

        $admin = new User();
        $admin->setEmail('admin@granota.com');
        $admin->setNickname('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));

        $manager->persist($admin);
        $manager->flush();
    }
}