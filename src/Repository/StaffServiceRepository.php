<?php

namespace App\Repository;

use App\Entity\StaffService;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StaffService|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffService|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffService[]    findAll()
 * @method StaffService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffServiceRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, StaffService::class);
    }

    public function getStaffServicesById(int $id): ?StaffService
    {
        return parent::findOneBy(['id' => $id])??null;
    }

    public function getStaffServicesByUser(int $id): ?array
    {
        return parent::findBy(['staff_id' => $id, 'is_deleted' => false])??null;
    }

    public function validate(array $services_input) : bool
    {
        foreach ($services_input['id'] as $i => $id) {
            if (
                !isset($id)
                || empty(trim($services_input['services'][$i]))
                || empty(trim($services_input['time'][$i]))
                || empty(trim($services_input['price'][$i]))
            ) {
                return false;
            }
        }
        return true;
    }

    public function setDate(User $user, array $services_input)
    {
        foreach ($services_input['id'] as $i => $id) {
            $staffServices = new StaffService();
            if ($id > 0 && $ss = $this->getStaffServicesById((int)$id)) {
                if ($ss) {
                    $staffServices = $ss;
                }
            }
            $staffServices->setTime(trim($services_input['time'][$i]));
            $staffServices->setPrice(trim($services_input['price'][$i]));
            $staffServices->setServiceId(trim($services_input['services'][$i]));
            $staffServices->setCompanyId($user->getCompanyId());
            $staffServices->setStaffId($user->getId());
            $staffServices->setIsDeleted(false);
            $this->manager->persist($staffServices);
            $this->manager->flush();
        }
    }

    public function removeById(int $id): bool
    {
        if ($id > 0 && $staffServices = $this->getStaffServicesById($id)) {
            $staffServices->setIsDeleted(true);
            $this->manager->persist($staffServices);
            $this->manager->flush();
            return true;
        }
        return false;
    }

    // /**
    //  * @return StaffService[] Returns an array of StaffService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StaffService
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
