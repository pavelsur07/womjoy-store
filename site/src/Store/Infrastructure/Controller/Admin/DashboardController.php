<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin;

use App\Matrix\Domain\Entity\Syncing\Key\Key;
use App\Matrix\Infrastructure\Repository\Syncing\KeyRepository;
use App\Matrix\Infrastructure\Wildberries\HttpRequest;
use App\Matrix\Infrastructure\Wildberries\Model\Statistics\ReportDetailByPeriod;
use App\Store\Infrastructure\Console\CategoryUpdateFilterCommand;
use App\Store\Infrastructure\Console\SitemapGenerateCommand;
use App\Store\Infrastructure\Service\YandexMarket\YandexMarket;
use DateTimeImmutable;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/admin/dashboard/wb', name: 'admin.dashboard.wb', methods: ['GET'])]
    public function getWb(MessageBusInterface $bus, KeyRepository $keys): Response
    {
        /** @var Key $key */
        foreach ($keys->list() as $key) {
            $keyValue = $key->getWildberriesKey()->getValue();

            $options = [
                'query' => [
                    'dateFrom' => (new DateTimeImmutable('2023-07-18'))->format('Y-m-d\TH:i:sP'),
                    'limit'=>100000,
                    'dateTo' => (new DateTimeImmutable('2023-08-18'))->format('Y-m-d\TH:i:sP'),
                ],
            ];

            $wbClient = new HttpRequest(
                baseUri: '',
                accessToken: $keyValue,
            );

            $response = $wbClient->get(
                url: 'https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod',
                options: $options,
            );

            foreach ($response as $item) {
                /* $report = $this->denormalize->denormalize(
                     data: $item,
                     type: ReportDetailByPeriod::class,
                 );*/
                $report = new ReportDetailByPeriod();
                $report->setKeyId(100);
                $report->setRealizationreportId((string)$item['realizationreport_id']);
                $report->setDateFrom(new DateTimeImmutable($item['date_from']));
                $report->setDateTo(new DateTimeImmutable($item['date_to']));
                $report->setCreateDt(new DateTimeImmutable($item['create_dt']));
                $report->setRrdId((string)$item['rrd_id']);
                $report->setRawData((array)$item);

                $bus->dispatch($report);
            }
        }

        return $this->redirectToRoute('admin.dashboard.show');
    }

    #[Route('/admin/dashboard/', name: 'admin.dashboard.show', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('admin/store/dashboard/show.html.twig');
    }

    #[Route(path: '/admin/dashboard/send-email', name: 'admin.dashboard.send_email')]
    public function sendMail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from('info@womjoy.ru')
            ->to(new Address('pavelsur07@gmail.com'))
            ->subject('Thanks for signing up!')

            // path of the Twig template to render
            /*->htmlTemplate('emails/signup.html.twig')*/
            ->htmlTemplate('pion/email/test.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'user' => 'Jon Konar',
            ]);
        /* $email = (new Email())
             ->from('hello@example.com')
             ->to('you@example.com')
             //->cc('cc@example.com')
             //->bcc('bcc@example.com')
             //->replyTo('fabien@example.com')
             //->priority(Email::PRIORITY_HIGH)
             ->subject('Time for Symfony Mailer!')
             ->text('Sending emails is fun again!')
             ->html('<p>See Twig integration for better HTML integration!</p>');
         */

        $mailer->send($email);

        return $this->redirectToRoute('admin.dashboard.show');
    }

    #[Route('/admin/dashboard/generate/yml', name: 'admin.dashboard.generate.yml', methods: ['GET'])]
    public function generateYml(YandexMarket $market): Response
    {
        $market->setProperty(company: 'WOMJOY', name: 'ВУМДЖОЙ ООО', url: 'https://womjoy.ru');
        $market->generate();
        return $this->redirectToRoute('admin.dashboard.show');
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/admin/category/update-filter', name: 'admin.dashboard.category.update.filter', methods: ['GET'])]
    public function updateFilter(CategoryUpdateFilterCommand $command): Response
    {
        $command->run(
            new ArrayInput([]),
            new NullOutput()
        );
        return $this->redirectToRoute('admin.dashboard.show');
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/admin/dashboard/generate/sitemap', name: 'admin.dashboard.generate.sitemap', methods: ['GET'])]
    public function generateSitemap(SitemapGenerateCommand $command): Response
    {
        $command->run(
            new ArrayInput([]),
            new NullOutput()
        );
        return $this->redirectToRoute('admin.dashboard.show');
    }
}
