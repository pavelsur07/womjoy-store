<?php

namespace App\DataFixtures\User;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public const REFERENCE_ADMIN = 'user_user_admin';
    public const REFERENCE_USER = 'user_user_user';
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setEmail('admin@app.test');
        $hashed = $this->hasher->hashPassword(
            user: $user,
            plainPassword: 'password'
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($hashed);
        $manager->persist($user);
        $manager->flush();
    }
}