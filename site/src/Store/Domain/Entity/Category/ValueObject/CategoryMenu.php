<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class CategoryMenu
{
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $value = null;

    public function addItem(string $itemId, string $name, string $href): void
    {
        $this->value[] = [
            'id' => $itemId,
            'name' => $name,
            'href' => $href,
        ];
    }

    public function clear(): void
    {
        $this->value = null;
    }

    public function removeItem(string $itemId): void
    {
        foreach ($this->value as $item) {
            if ($item['id'] === $itemId) {
                $this->removeElement($item);
                return;
            }
        }
    }

    public function removeElement(mixed $element): bool
    {
        $key = array_search($element, $this->value, true);

        if ($key === false) {
            return false;
        }

        unset($this->value[$key]);

        return true;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }
}
