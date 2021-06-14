<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Position|null find($id, $lockMode = null, $lockVersion = null)
 * @method Position|null findOneBy(array $criteria, array $orderBy = null)
 * @method Position[]    findAll()
 * @method Position[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PositionRepository extends ServiceEntityRepository implements PositionRepositoryInterface
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Position::class);
    }
    public function getAllPosition(): array
    {
        return parent::findAll();
    }

    public function getPositionByCompany(int $company_id): array
    {
        return parent::findBy(['company_id' => $company_id]);
    }

    public function getPosition(int $position_id): object
    {
        return parent::find($position_id);
    }

    public function insertFormPosition(Position $position): object
    {
        $this->manager->persist($position);
        $this->manager->flush();
        return $position;
    }

    public function setDismissPosition(Position $position)
    {
        // TODO: Implement setDismissUser() method.
    }
}
