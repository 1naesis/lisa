<?php

namespace App\Repository;

use App\Entity\ManualTimetable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ManualTimetable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManualTimetable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManualTimetable[]    findAll()
 * @method ManualTimetable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManualTimetableRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, ManualTimetable::class);
    }

    public function findManualTimetableByDateAndUser (\DateTimeInterface $date, int $user_id): ?ManualTimetable
    {
        return parent::findOneBy(['date' => $date, 'user_id' => $user_id])??null;
    }

    public function changeManualTimetable ($data): ?int
    {
        $action = 1;
        $manualTimetable = $this->findManualTimetableByDateAndUser($data['date'], $data['user_id']);
        if(isset($manualTimetable)){
            $timetable = $manualTimetable->getTimetable();
            if (in_array($data['time'], $timetable)) {
                $action = 2;
                $tmp_time = [];
                foreach ($timetable as $t) {
                    if ($t != $data['time']) {
                        $tmp_time[] = $t;
                    }
                }
                $timetable = $tmp_time;
            } else {
                $timetable[] = $data['time'];
            }
            $data['time'] = $timetable;
        }
        if (!$manualTimetable) {
            $manualTimetable = new ManualTimetable();
            $data['time'] = [$data['time']];
        }
        $manualTimetable->setDate($data['date']);
        $manualTimetable->setTimetable($data['time']);
        $manualTimetable->setUserId($data['user_id']);
        $manualTimetable->setCompanyId($data['company_id']);
        $this->manager->persist($manualTimetable);
        $this->manager->flush();

        return $action;
    }

    // /**
    //  * @return ManualTimetable[] Returns an array of ManualTimetable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ManualTimetable
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
