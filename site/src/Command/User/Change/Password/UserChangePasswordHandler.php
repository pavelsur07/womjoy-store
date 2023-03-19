<?php

namespace App\Command\User\Change\Password;

use App\Repository\Flusher;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserChangePasswordHandler
{
    public function __construct(
        private UserRepository              $users,
        private Flusher                     $flusher,
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    public function __invoke(UserChangePasswordCommand $command): void
    {
        $user = $this->users->getByEmail($command->getEmail());
        $hashed = $this->hasher->hashPassword(
            user: $user,
            plainPassword: $command->getPassword()
        );

        $user->setPassword($hashed);
        $this->flusher->flush();

    }
}