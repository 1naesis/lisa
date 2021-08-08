<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Client::class);
    }

    public function findAllByCompany(int $company_id): ?array
    {
        return parent::findBy(['company_id' => $company_id])??null;
    }

    public function findByIdAndCompany(int $id, int $company_id): ?Client
    {
        return parent::findOneBy(['id' => $id, 'company_id' => $company_id])??null;
    }

    public function validation(array $input): bool
    {
        if (
            empty(trim($input['name'])) &&
            empty(trim($input['surname'])) &&
            empty(trim($input['phone'])) &&
            empty(trim($input['comment']))
        ) {
            return false;
        }
        return true;
    }
    public function setData(Client $client, array $input): Client
    {
        $client->setName($input['name']);
        $client->setSurname($input['surname']);
        $client->setPhone($input['phone']);
        $client->setComment($input['comment']);
        return $client;
    }

    public function insertClient(Client $client): Client
    {
        $this->manager->persist($client);
        $this->manager->flush();
        return $client;
    }
    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
