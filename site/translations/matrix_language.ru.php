<?php

declare(strict_types=1);

use App\Matrix\Domain\Entity\ValueObject\ProductStatus;

return [
    ProductStatus::DRAFT => 'Черновик',
    ProductStatus::DEVELOPMENT => 'Разрабатывается',
    ProductStatus::READY_DEVELOPMENT => 'Разработан',
    ProductStatus::WAIT_SALE => 'Готовим к продаже',
    ProductStatus::READY_SALE => 'Готов к продаже',
    ProductStatus::SALE =>'Продаем',
    ProductStatus::ARCHIVED => 'Архивный',
];