<?php

declare(strict_types=1);

namespace App\Menu\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`menu_setting`')]
class MenuSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Menu::class)]
    private Menu|null $footerMenu;

    public function __construct(
        Menu $footerMenu,
    ) {
        $this->footerMenu = $footerMenu;
    }

    public function changeFooterMenu(Menu $menu): void
    {
        $this->footerMenu = $menu;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFooterMenu(): ?Menu
    {
        return $this->footerMenu;
    }
}
