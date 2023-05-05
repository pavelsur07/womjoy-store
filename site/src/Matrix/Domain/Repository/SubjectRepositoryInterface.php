<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Repository;

use App\Matrix\Domain\Entity\Subject;

interface SubjectRepositoryInterface
{
    public function get(int $id): Subject;

    public function list(): array;

    public function save(Subject $subject, bool $flush = false): void;

    public function remove(Subject $subject, bool $flush = false): void;
}
