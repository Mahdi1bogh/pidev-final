<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashbordController extends AbstractController
{
    #[Route('/admin/dashbord', name: 'app_admin_dashbord')]
    public function index(): Response
    {
        return $this->render('admin_dashbord/index.html.twig', [
            'controller_name' => 'AdminDashbordController',
        ]);
    }
}
