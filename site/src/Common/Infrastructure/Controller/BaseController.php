<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Controller;

use App\Common\Infrastructure\JsonLd\JsonLdCompany;
use App\Common\Infrastructure\JsonLd\JsonLdGenerator;
use App\Menu\Domain\Entity\Menu;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use App\Menu\Infrastructure\Service\MenuSettingService;
use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Home\AssignCategory;
use App\Store\Domain\Entity\Home\Home;
use App\Store\Domain\Service\HomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseController extends AbstractController
{
    public array $menu = [
        'top' => [],
        'header' => [],
        'footer' => [],
        'categories' => [],
    ];

    public array $metaData =[
        'base_url' => '',
        'title' =>'Title default base controller',
        'description' => 'Description default base controller',
        'h1' => 'H1 default base controller',
        'noindex' => false,
        'nofollow' => false,
        'jsonLdCompany' => null,
        'jsonLdBreadcrumb' => null,
        'phone'=> null,
        'email'=> null,
        'company' => null,
    ];

    public string $template = 'pion';

    public function __construct(
        private readonly MenuRepositoryInterface $menus,
        private readonly HomeService $homeService,
        private readonly UrlGeneratorInterface $generator,
        private readonly MenuSettingService $menuService,
        private readonly SettingService $settingService,
        private readonly string $siteUrl,
    ) {
        $headerMenu = $this->menus->findById(id: 1);
        if ($headerMenu === null) {
            $headerMenu = new Menu(name: 'Header menu', href: '/');
            $headerMenu->setRoot(1);
            $this->menus->save(object: $headerMenu, flush: true);

            $count = \count($headerMenu->getChildren());
            $item = new Menu(name: 'Home page', href: '/');
            $item->setParent($headerMenu);
            $item->setSort($count++);
            $this->menus->save($item, true);
        }

        $this->menu['header'] = $this->menus->menuTree($headerMenu);
        $footerMenu = $this->menuService->getSetting()->getFooterMenu();
        $this->menu['footer'] =  $footerMenu !== null ? $this->menus->menuTree($footerMenu) : [];

        $home = $this->homeService->get();
        $this->menu['categories'] = $this->menuCategories($home);

        $setting = $this->settingService->get();
        $this->metaData['base_url'] = $this->siteUrl;
        $this->metaData['title'] = $setting->getSeoDefault()->getTitle();
        $this->metaData['description'] = $setting->getSeoDefault()->getDescription();
        $this->metaData['h1'] = $setting->getSeoDefault()->getH1();
        $this->metaData['phone'] = $setting->getPhone();
        $this->metaData['email'] = $setting->getEmail();
        $this->metaData['company'] = $setting->getCompany() ? $setting->getCompany()->getName() : '';
        $this->metaData['jsonLdCompany'] = JsonLdGenerator::generate(
            JsonLdCompany::get(
                name: $setting->getCompany() ? $setting->getCompany()->getName() : '',
                url: $this->siteUrl,
                logo: $this->siteUrl . '/img/logo.svg',
                postalCode: $setting->getCompany() !==null ? $setting->getCompany()->getPostalCode() : '',
                addressCountry: $setting->getCompany() !==null ? $setting->getCompany()->getAddressCountry() : '',
                addressLocality: $setting->getCompany() !==null ? $setting->getCompany()->getAddressLocality() : '',
                streetAddress: $setting->getCompany() !==null ? $setting->getCompany()->getStreetAddress() : '',
                telephone: $setting->getPhone() !== null ? $setting->getPhone() : '',
                email: $setting->getEmail() !== null ? $setting->getEmail() : '',
            )
        );
    }

    public function setTitle(string $title): void
    {
        $this->metaData['title'] = $title;
    }

    public function setDescription(string $description): void
    {
        $this->metaData['description'] = $description;
    }

    public function setH1(string $h1): void
    {
        $this->metaData['h1'] = $h1;
    }

    /** @deprecated  */
    public function jsonLdGenerator(array $data): string
    {
        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    public function breadcrumbsCategoryGenerate(Category $category, ?array $bread = null): array
    {
        $bread[] = [
            'name' =>$category->getName(),
            'slug' => $category->getSlug(),
            'href' => $this->generateUrl('store.category.show', ['slug' => $category->getSlug()]),
        ];

        if ($category->getParent() !== null) {
            return $this->breadcrumbsCategoryGenerate($category->getParent(), $bread);
        }
        return array_reverse($bread);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    private function menuCategories(Home $home): array
    {
        $result = [];
        /** @var AssignCategory $category */
        foreach ($home->getCategories() as $category) {
            $result[] = [
                'name' => $category->getCategory()->getName(),
                'href' => $this->generator->generate('store.category.show', ['slug' => $category->getCategory()->getSlug()]),
                'imagePath' =>  $category->getCategory()->getImage() === null ? '' : $category->getCategory()->getImage()->getPath() . '/' . $category->getCategory()->getImage()->getName(),
            ];
        }

        return $result;
    }
}
