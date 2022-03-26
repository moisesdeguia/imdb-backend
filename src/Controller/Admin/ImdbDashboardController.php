<?php

namespace App\Controller\Admin;

use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Movie;
use App\Entity\Genre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImdbDashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator){

    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $url = $this->adminUrlGenerator
                    ->setController(MovieCrudController::class)
                    ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Imdb');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToCrud('Movies', 'fa fa-film', Movie::class);
        yield MenuItem::linkToCrud('Directors', 'fa fa-video', Director::class);
        yield MenuItem::linkToCrud('Actors', 'fa fa-male', Actor::class);
        yield MenuItem::linkToCrud('Genres', 'fa fa-ticket', Genre::class);
    }
}
