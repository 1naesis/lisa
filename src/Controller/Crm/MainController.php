<?php

namespace App\Controller\Crm;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/lk", name="lk")
     */
    public function index(): Response
    {
        return $this->render('crm/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
