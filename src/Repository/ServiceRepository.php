<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Service::class);
    }

    public function getService(int $id): ?Service
    {
        return parent::find($id)??null;
    }

    public function getServicesByCompany(int $company_id): ?array
    {
        return parent::findBy(['company_id' => $company_id])??null;
    }

    public function getServicesByCompanyAndCatalog(int $company_id, int $category_id): ?array
    {
        return parent::findBy(['company_id' => $company_id, 'category_id' => $category_id])??null;
    }

    public function validation(array &$data): bool
    {
        $success = true;
        if (!empty(trim($data['title']))) {
            $data['title'] = htmlspecialchars(trim($data['title']));
        } else {
            $success = false;
        }
        if ($data['category_id'] >= 0 ) {
            $data['category_id'] = (int)$data['category_id'];
        } else {
            $success = false;
        }
        return $success;
    }

    public function setData(Service $service, array $data): void
    {
        $service->setTitle($data['title']);
        $service->setCompanyId($data['company_id']);
        $service->setCategoryId($data['category_id']);
    }

    public function insertService(Service $service): Service
    {
        $this->manager->persist($service);
        $this->manager->flush();
        return $service;
    }
}
