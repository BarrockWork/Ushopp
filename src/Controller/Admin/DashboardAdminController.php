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
    public function index(StatsService $statsService, OrderShopRepository $orderShopRepository, CommentRepository $commentRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $stats = $statsService->getStats();
        $bestCustomers = $orderShopRepository->theBiggestBuyers();
        $lastOrders = $orderShopRepository->getLastOrders();
        $lastComment = $commentRepository->getLastComments();

        $soldProductsOfMonthsChart = $this->chartSoldProductsOfMonth($chartBuilder);
        $salesMonthly = $this->chartSalesMonthly($chartBuilder);

        $soldRevenuesLast3Months= $this->chartSoldRevenuesLast3Months($chartBuilder);
        $soldRevenuesPerCategory = $this->chartSoldRevenuesPerCategories($chartBuilder);

        return $this->render('admin/dashboard/index_admin.html.twig', [
            'stats' => $stats,
            'lastOrders' => $lastOrders,
            'lastComments' => $lastComment,
            'bestCustomers' => $bestCustomers,
            'salesMonthly' => $salesMonthly,
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
                    $datasProduct[$keyDates] += round($datas['revenues'],2);

                }else{
                    $datasProduct[$keyDates] = round($datas['revenues'],2);
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
                $datasProduct[]= round($datas['revenues'],2);
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

    private function chartSalesMonthly(ChartBuilderInterface $chartBuilder){

        $datas = $this->em->getRepository(OrderShop::class)->getAllSalesByMonth();
        $labels = [];
        $dataSales = [];
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

        $tmpPaymentAt = [];


        if(count($datas) > 0) {
            foreach ($datas as $data) {
                // labels
                // si il n'y a pas de date, alors on rajoute une nouvelle date
                if (!in_array($data['paymentAt']->format('d-M-Y'), $labels)){
                    $labels[]= $data['paymentAt']->format('d-M-Y');
                }

                //dataSales

                // si $tmpPaymentAt est vide alors on rajoute la date d'achat + le nombre de commande = 1
                if (!in_array($data['paymentAt']->format('d-M-Y'), $tmpPaymentAt)){
                    $tmpPaymentAt[]= $data['paymentAt']->format('d-M-Y');
                    $dataSales[] = 1;
                }
                // sinon on incremente le nombre de commande
                else{
                  $keyPaymentAt = array_search( $data['paymentAt']->format('d-M-Y'),$tmpPaymentAt);
                  $dataSales[$keyPaymentAt]+= 1;
                }
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

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0]],
                ],
            ],
        ]);

        return $chart;
    }
}
