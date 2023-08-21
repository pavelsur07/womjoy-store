<?php

declare(strict_types=1);

namespace App\Matrix\Application\Command\Syncing;

use App\Matrix\Domain\Entity\Syncing\Wildberries\ReportDetailByPeriod;
use App\Matrix\Infrastructure\Repository\Syncing\ReportDetailByPeriodRepository;
use App\Matrix\Infrastructure\Wildberries\Model\Statistics\ReportDetailByPeriod as Message;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

readonly class ReportDetailByPeriodSyncingHandler
{
    public function __construct(
        private ReportDetailByPeriodRepository $reports,
    ) {
    }

    #[AsMessageHandler]
    public function __invoke(Message $message): void
    {
        $report = new ReportDetailByPeriod(
            keyId: $message->getKeyId(),
            realizationreportId: $message->getRealizationreportId(),
            dateFrom: $message->getDateFrom(),
            dateTo: $message->getDateTo(),
            createDt: $message->getCreateDt(),
            rrdId: $message->getRrdId(),
            rawData: $message->getRawData()
        );

        $this->reports->save($report, true);
    }
}
