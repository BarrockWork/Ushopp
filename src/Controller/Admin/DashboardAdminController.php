<?php

namespace App\Controller\Admin;

use App\Repository\OrderShopRepository;
use App\Services\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardAdminController extends AbstractController
{
    /**
     * Dashboard of the admin
     * @Route("/{_locale}/admin", name="admin_dashboard")
     */
    public function index(StatsService $statsService, OrderShopRepository $repo, ChartBuilderInterface $chartBuilder): Response
    {
        $stats = $statsService->getStats();

        $lastOrders = $repo->getLastOrders();

        $orders = $repo->findByIsPaid(true);

        $labels = [];
        $data = [];

        foreach ($orders as $order){

            $labels[] = $order->getCreatedAt()->format('d-m-Y');;
            $data[] = count(array($order));
        }


        return $this->render('admin/dashboard/index_admin.html.twig', [
            'stats' => $stats,
            'lastOrders' => $lastOrders,
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
