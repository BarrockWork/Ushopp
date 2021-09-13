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
        $soldRevenuesLast3Months= $this->chartSoldRevenuesLast3Months($chartBuilder);
        $soldRevenuesPerCategory = $this->chartSoldRevenuesPerCategories($chartBuilder);

        return $this->render('admin/dashboard/index_admin.html.twig', [
            'stats' => $stats,
            'lastOrders' => $lastOrders,
            'labels' => $labels,
            'data' => $data,
            'lastComments' => $lastComment,
            'soldProductsOfMonthsChart' => $soldProductsOfMonthsChart,
            'soldRevenuesLast3Months' => $soldRevenuesLast3Months,
            'soldRevenuesPerCategory' => $soldRevenuesPerCategory
        ]);
    }

    private function chartSoldProductsOfMonth(ChartBuilderInterface $chartBuilder) {

        //id: idOrderDetail, quantity
        $datasSQL = $this->em->getRepository(OrderDetails::class)->getProductsOfMonths();
        $labels = [];
        $dataproduct = [];
        $backGroundColors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(205, 92, 92)',
            'rgb(255, 160, 122)',
            'rgb(72, 201, 176)',
            'rgb(112, 123, 124)',
            'rgb(155, 89, 182 )',
            'rgb(36, 113, 163)',
            'rgb(241, 196, 15)',
            'rgb(19, 141, 117)',
            'rgb(249, 231, 159)',
            'rgb(39, 174, 96)',
            'rgb(133, 193, 233)',
            'rgb(186, 74, 0)',
            'rgb(46, 64, 83)',
            'rgb(236, 112, 99)',
            'rgb(244, 208, 63)',
            'rgb(174, 214, 241)',
            'rgb(244, 208, 63)',
            'rgb(127, 179, 213)'
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

    private function chartSoldRevenuesLast3Months(ChartBuilderInterface $chartBuilder) {

        //id: idOrderDetail, quantity
        $datasSQL = $this->em->getRepository(OrderDetails::class)->getRevenuesLast3Months();
        $labels = [];
        $datasProduct = [];
        $tmpDatesRevenues = [];

        if(count($datasSQL) > 0) {
            foreach ($datasSQL as $datas) {
                if(!in_array($datas['paymentAt']->format('Y-m-d'), $labels)) {
                    $labels[]= $datas['paymentAt']->format('Y-m-d');
                }

                if(!in_array($datas['paymentAt']->format('Y-m-d'), $tmpDatesRevenues)) {
                    $tmpDatesRevenues[] = $datas['paymentAt']->format('Y-m-d');
                }
                $keyDates = array_search($datas['paymentAt']->format('Y-m-d'), $tmpDatesRevenues);

                if(array_key_exists($keyDates, $datasProduct)) {
                    $datasProduct[$keyDates] += $datas['revenues'];

                }else{
                    $datasProduct[$keyDates] = $datas['revenues'];
                }

            }
        }

        //  chartjs
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->translator->trans('chart.ca.month'),
                    'borderColor'=> 'rgb(75, 192, 192)',
                    'data' => $datasProduct,
                    'fill' => false,
                    'tension' => 0.1
                ],
            ],
        ]);
        return $chart;
    }

    private function chartSoldRevenuesPerCategories(ChartBuilderInterface $chartBuilder) {

        //id: idOrderDetail, quantity
        $datasSQL = $this->em->getRepository(OrderDetails::class)->getRevenuesPerCategory();
        $labels = [];
        $datasProduct = [];

        if(count($datasSQL) > 0) {
            foreach ($datasSQL as $datas) {
                $labels[]= $datas['name'];
                $datasProduct[]= $datas['revenues'];
            }
        }
        $backGroundColors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(205, 92, 92)',
            'rgb(255, 160, 122)',
            'rgb(72, 201, 176)',
            'rgb(112, 123, 124)',
            'rgb(155, 89, 182 )',
            'rgb(36, 113, 163)',
            'rgb(241, 196, 15)',
            'rgb(19, 141, 117)',
            'rgb(249, 231, 159)',
            'rgb(39, 174, 96)',
            'rgb(133, 193, 233)',
            'rgb(186, 74, 0)',
            'rgb(46, 64, 83)',
            'rgb(236, 112, 99)',
            'rgb(244, 208, 63)',
            'rgb(174, 214, 241)',
            'rgb(244, 208, 63)',
            'rgb(127, 179, 213)'
        ];

        //  chartjs
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->translator->trans('chart.ca.category'),
                    'backgroundColor' => $backGroundColors,
                    'borderColor' => $backGroundColors,
                    'data' => $datasProduct,
                ],
            ],
        ]);
        return $chart;
    }

}
