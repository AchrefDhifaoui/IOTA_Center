<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail("achref.dhifaoui00@gmail.com");
        $admin->setPassword($this->hasher->hashPassword($admin,'achref3116'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setNom('DHIFAOUI');
        $admin->setPrenom('Achref');
        $manager->persist($admin);
        $manager->flush();
    }
    public static function getGroups():array
    {
        return ['user'];
    }
}
