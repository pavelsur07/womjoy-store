<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Customer;

use App\Store\Domain\Entity\Customer\Customer;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerService
{
    public static function get(): Customer
    {
        return new Customer();
    }

    public function create(UserInterface $user): Customer
    {
        return new Customer();
    }
}
