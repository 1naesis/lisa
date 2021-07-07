<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Category::class);
    }

    public function getCategory(int $id): ?Category
    {
        return parent::find($id)??null;
    }

    public function getCategoriesByCompany(int $id): ?array
    {
        return parent::findBy(['company_id' => $id])??null;
    }

    public function validation(array $data): bool
    {
        if (!empty(trim($data['title']))) {
            return true;
        }
        return false;
    }

    public function setData(Category $category, array $data): void
    {
        $category->setTitle(htmlspecialchars(trim($data['title'])));
        $category->setCompanyId($data['company_id']);
    }

    public function insertCategory($category): Category
    {
        $this->manager->persist($category);
        $this->manager->flush();
        return $category;
    }
}
