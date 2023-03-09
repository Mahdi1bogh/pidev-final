<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentDashbordController extends AbstractController
{
    #[Route('/agent/dashbord', name: 'app_agent_dashbord')]
    public function index(): Response
    {
        return $this->render('agent_dashbord/index.html.twig', [
            'controller_name' => 'AgentDashbordController',
        ]);
    }
}
