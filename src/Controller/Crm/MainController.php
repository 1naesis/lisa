<?php

namespace App\Controller\Crm;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends BasicController
{
    /**
     * @Route("/info", name="info")
     */
    public function info(): Response
    {
        return $this->render('crm/main/info.html.twig', [
            'controller_name' => 'MainController'
        ]);
    }

    /**
     * @Route("/lk", name="lk")
     */
    public function index(): Response
    {
        return $this->render('crm/main/index.html.twig', [
        ]);
    }

    /**
     * @Route("/lk/order", name="lk/order")
     */
    public function order(): Response
    {
        return $this->render('crm/main/index.html.twig', [
        ]);
    }

    /**
     * @Route("/lk/client/{client_id<\d+>}", name="lk/client_edit")
     */
    public function clientEdit(int $client_id, ClientRepository $clientRepository, Request $request): Response
    {
        $user = $this->getUser();
        $company = $this->getThisCompany();

        $client_input = $request->request->get('client');

        $client = new Client();
        if ($client_id && $client_id > 0) {
            $client = $clientRepository->findByIdAndCompany($client_id, $company->getId());
        }

        if ($client_input && $clientRepository->validation($client_input)) {
            $client->setCompanyId($company->getId());
            $clientRepository->setData($client, $client_input);
            $clientSaved = $clientRepository->insertClient($client);
            return $this->redirectToRoute('lk/client_edit', ['client_id' => $clientSaved->getId()]);
        }

        $activeMenu = 'client';
        return $this->render('crm/main/client/edit.html.twig', [
            'client' => $client,
            'activeMenu' => $activeMenu
        ]);
    }

    /**
     * @Route("/lk/client", name="lk/client")
     */
    public function clients(ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();
        $company = $this->getThisCompany();

        $clients = $clientRepository->findAllByCompany($company->getId());

        $activeMenu = 'client';
        return $this->render('crm/main/client/index.html.twig', [
            'clients' => $clients,
            'activeMenu' => $activeMenu
        ]);
    }
}
