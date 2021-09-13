<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
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

        $orders = $repo->findByIsPaid(true);

        $labels = [];
        $data = [];

        foreach ($orders as $order){

            $labels[] = $order->getCreatedAt()->format('d-m-Y');;
            $data[] = count(array($order));
        }

        $soldProductsOfMonthsChart = $this->chartSoldProductsOfMonth($chartBuilder);
        $chart = $this->createChart($chartBuilder);

        return $this->render('admin/dashboard/index_admin.html.twig', [
            'stats' => $stats,
            'lastOrders' => $lastOrders,
            'labels' => $labels,
            'data' => $data,
            'lastComments' => $lastComment,
            'chart' => $chart,
            'soldProductsOfMonthsChart' => $soldProductsOfMonthsChart
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
}
