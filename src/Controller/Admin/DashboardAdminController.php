<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use App\Entity\OrderShop;
use App\Repository\CommentRepository;
use App\Repository\OrderShopRepository;
use App\Services\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardAdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator) {
        $this->em=$em;
        $this->translator = $translator;
    }
    /**
     * Dashboard of the admin
     * @Route("/{_locale}/admin", name="admin_dashboard")
     */
    public function index(StatsService $statsService, OrderShopRepository $repo, CommentRepository $commentRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $stats = $statsService->getStats();

        $lastOrders = $repo->getLastOrders();
        $lastComment = $commentRepository->getLastComments();

        $soldProductsOfMonthsChart = $this->chartSoldProductsOfMonth($chartBuilder);
        $salesMonthly = $this->chartSalesMonthly($chartBuilder);

        $chart = $this->createChart($chartBuilder);

        return $this->render('admin/dashboard/index_admin.html.twig', [
            'stats' => $stats,
            'lastOrders' => $lastOrders,
            'lastComments' => $lastComment,
            'chart' => $chart,
            'soldProductsOfMonthsChart' => $soldProductsOfMonthsChart,
            'salesMonthly' => $salesMonthly,
        ]);
    }

    private function createChart(ChartBuilderInterface $chartBuilder) {
        //  chartjs
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 100]],
                ],
            ],
        ]);

        return $chart;
    }


    private function chartSoldProductsOfMonth(ChartBuilderInterface $chartBuilder) {

        //id: idOrderDetail, quantity
        $datasSQL = $this->em->getRepository(OrderDetails::class)->getProductsOfMonths();
        $labels = [];
        $dataproduct = [];
        $backGroundColors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
        ];

        if(count($datasSQL) > 0) {
            foreach ($datasSQL as $datas) {
                $labels[]= $datas['name'];
                $dataproduct[]= $datas['quantity'];

            }
        }

        //  chartjs
        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->translator->trans('chart.product.doughutLastMonth'),
                    'backgroundColor' => $backGroundColors,
                    'data' => $dataproduct,
                ],
            ],
        ]);
        return $chart;
    }

    private function chartSalesMonthly(ChartBuilderInterface $chartBuilder){

        $datas = $this->em->getRepository(OrderShop::class)->getAllSalesByMonth();
        $labels = [];
        $dataSales = [];
        $backGroundColors = [
            'rgb(255, 99, 132)',
            'rgb(51, 175, 255)',
            'rgb(51, 255, 85)',
            'rgb(255, 99, 132)',
            'rgb(255, 51, 249)',
            'rgb(255, 99, 132)',
            'rgb(255, 79, 51 )',
            'rgb(240, 255, 51)',
            'rgb(51, 255, 97 )',

        ];

        if(count($datas) > 0) {
            foreach ($datas as $data) {
                $labels[]= $data['paymentAt']->format('d-M-Y');
                $dataSales[]= $data['orders'];
            }
        }

        //  chartjs
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->translator->trans('chart.order.title'),
                    'backgroundColor' => $backGroundColors,
                    'data' => $dataSales,
                ],
            ],
        ]);
        return $chart;
    }
}
