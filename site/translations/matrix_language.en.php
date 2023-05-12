<?php

declare(strict_types=1);

use App\Matrix\Domain\Entity\ValueObject\ProductStatus;

return [
    ProductStatus::DRAFT => 'Draft',
    ProductStatus::DEVELOPMENT => 'Development',
    ProductStatus::READY_DEVELOPMENT => 'Ready development',
    ProductStatus::WAIT_SALE => 'Wait sale',
    ProductStatus::READY_SALE => 'Ready sale',
    ProductStatus::SALE =>'Sale',
    ProductStatus::ARCHIVED => 'Archived',
];