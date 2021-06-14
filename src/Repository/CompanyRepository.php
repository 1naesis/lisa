<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\InputBag;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Company::class);
    }

    public function getCompanyById(int $id): ?Company
    {
        return parent::find($id);
    }

    public function getCompanyByUser(int $id): ?Company
    {
        return parent::findOneBy(['user_id' => $id]);
    }

    public function validation(array $input): bool
    {
        if (empty(trim($input['title']))) {
            return false;
        }
        if(count($input['timetable']) !== 7) {
            return false;
        }
        return true;
    }

    public function setData(Company $company, array $input): Company
    {
        $title = htmlspecialchars(trim($input['title']));
        $description = htmlspecialchars(trim($input['description']));
        $timetable = [];
        foreach ($input['timetable'] as $w => $day) {
            list($h, $m) = explode('-', $day['start']);
            $start = preg_replace('/[^0-9]/', '', $h).'-'.preg_replace('/[^0-9]/', '', $m);
            list($h, $m) = explode('-', $day['end']);
            $end = preg_replace('/[^0-9]/', '', $h).'-'.preg_replace('/[^0-9]/', '', $m);
            $timetable[$w] = [$start, $end];
        }
        $company->setTitle($title);
        $company->setDescription($description);
        $company->setTimetable($timetable);
        $company->setUserId($input['user']);
        return $company;
    }

    public function insertCompany(Company $company): Company
    {
        $this->manager->persist($company);
        $this->manager->flush();
        return $company;
    }
}
