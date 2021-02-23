<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EcommerceUdemy');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // Ajoutés grâce à la commande => symfony console make:admin:crud
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-fas fa-user', User::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-fas fa-bookmark', Category::class);
    }
}
