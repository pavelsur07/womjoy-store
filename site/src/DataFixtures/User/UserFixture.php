<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Auth\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const REFERENCE_ADMIN = 'user_user_admin';
    public const REFERENCE_USER = 'user_user_user';

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

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
