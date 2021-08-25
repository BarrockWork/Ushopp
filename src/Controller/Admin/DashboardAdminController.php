<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardAdminController extends AbstractController
{
    /**
     * Dashboard of the admin
     * @Route("/{_locale}/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard/index_admin.html.twig');
    }
}
