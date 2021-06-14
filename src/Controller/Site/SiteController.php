<?php

namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('lk');
        return $this->render('site/index.html.twig', [
            'company_name' => 'Lisa',
        ]);
    }
}
