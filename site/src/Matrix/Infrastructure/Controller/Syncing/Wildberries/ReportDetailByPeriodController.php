<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Syncing\Wildberries;

use App\Matrix\Domain\Entity\Syncing\Wildberries\ReportDetailByPeriod;
use App\Matrix\Infrastructure\Repository\Syncing\ReportDetailByPeriodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/syncing/report-detail-by-period', name: 'matrix.admin.syncing.report_detail_by_period')]
class ReportDetailByPeriodController extends AbstractController
{
    public const PER_PAGE = 25;

    #[Route(path: '/', name: '.index')]
    public function index(Request $request, ReportDetailByPeriodRepository $reports): Response
    {
        return $this->render(
            'admin/matrix/syncing/report_detail_by_period/index.html.twig',
            [
                'pagination' => $reports->getAll(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                ),
            ]
        );
    }

    #[Route(path: '/{id}', name: '.show')]
    public function show(int $id, ReportDetailByPeriod $report, Request $request, ReportDetailByPeriodRepository $reports): Response
    {
        $object = (array)$report->getRawData();
        $subject = $object['subject_name'];

        return $this->render(
            'admin/matrix/syncing/report_detail_by_period/show.html.twig',
            [
                'report'=> $report,
                'subject' => $subject,
            ]
        );
    }
}
