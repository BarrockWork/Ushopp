<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * Homepage of the dashboard
     *
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<h1>Ushopp</h1>')
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Acceuil', 'fa fa-home');
        // User section
        yield MenuItem::section('Utilisateur');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        // Category section
        yield MenuItem::section('Catégorie');
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-list', Category::class);
        // Product section
        yield MenuItem::section('Produit');
        yield MenuItem::linkToCrud('Produit', 'fas fa-tag', Product::class);
    }
}
