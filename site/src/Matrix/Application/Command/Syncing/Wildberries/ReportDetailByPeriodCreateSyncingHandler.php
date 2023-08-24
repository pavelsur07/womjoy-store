<?php

declare(strict_types=1);

namespace App\Matrix\Application\Command\Syncing\Wildberries;

use App\Matrix\Infrastructure\Wildberries\HttpRequest;
use App\Matrix\Infrastructure\Wildberries\Model\Statistics\ReportDetailByPeriod;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class ReportDetailByPeriodCreateSyncingHandler
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ReportDetailByPeriodCreateSyncingTask $command): void
    {
        $options = [
            'query' => [
                'dateFrom' => $command->getDateFrom()->format('Y-m-d\TH:i:sP'),
                // (new DateTimeImmutable('2023-07-18'))->format('Y-m-d\TH:i:sP'),
                'limit'=>100000,
                'dateTo' => $command->getDateTo()->format('Y-m-d\TH:i:sP'),
                // (new DateTimeImmutable('2023-08-18'))->format('Y-m-d\TH:i:sP'),
            ],
        ];

        $wbClient = new HttpRequest(
            baseUri: '',
            accessToken: $command->getKeyValue(),
        );

        $response = $wbClient->get(
            url: 'https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod',
            options: $options,
        );

        foreach ($response as $item) {
            $report = new ReportDetailByPeriod();
            $report->setKeyId(100);
            $report->setRealizationreportId((string)$item['realizationreport_id']);
            $report->setDateFrom(new DateTimeImmutable($item['date_from']));
            $report->setDateTo(new DateTimeImmutable($item['date_to']));
            $report->setCreateDt(new DateTimeImmutable($item['create_dt']));
            $report->setRrdId((string)$item['rrd_id']);
            $report->setRawData((array)$item);

            $this->bus->dispatch($report);
        }
    }
}
