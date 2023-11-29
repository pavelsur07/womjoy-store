<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use App\Store\Domain\Exception\StoreOrderException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderAmoCRM
{
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isCreateLead = false;

    public function isCreated(): bool
    {
        return $this->isCreateLead === true;
    }

    public function created(): void
    {
        if ($this->isCreateLead) {
            throw new StoreOrderException('Already created lead.');
        }
        $this->isCreateLead = true;
    }
}
