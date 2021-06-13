<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\User;
use App\Service\FileManager\FIleManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    private $manager;
    private $passwordEncoder;
    private $fileManager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $passwordEncoder,
        FIleManagerInterface $fileManager
    )
    {
        $this->fileManager = $fileManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        parent::__construct($registry, User::class);
    }

    public function getAllUser(): array
    {
        return parent::findAll();
    }

    public function getUser(int $user_id): ?object
    {
        return parent::find($user_id)??null;
    }

    public function getUserByCompany(int $company_id): array
    {
        return parent::findBy(['company_id' => $company_id]);
    }

    public function insertFormUser(User $user, FormInterface $form): object
    {
        if($user->getId()){
            if(!empty(trim($form->get('new_password')->getData()))){
                $password = $this->passwordEncoder->encodePassword($user, $form->get('new_password')->getData());
                $user->setPassword($password);
            }
            if(!empty(trim($form->get('avatar')->getData()))){
                $avatar = $this->fileManager->saveFile($form['avatar']->getData(), 'staff_directory');
                if($avatar){
                    $this->fileManager->removeFile($user->getAvatar(), 'staff_directory');
                    $user->setAvatar($avatar);
                }
            }
            return $this->setUpdateUser($user);
        }else{
            $password = $this->passwordEncoder->encodePassword($user, $form->get('new_password')->getData());
            $user->setAvatar($form->get('avatar')->getData());
            $user->setPassword($password);
            return $this->setCreateUser($user);
        }
    }

    public function setTimetable(User $user, Company $company, array $timetable): void
    {
        $companyTimetable = $company->getTimetable();
        $tmp = [];
        foreach ($companyTimetable as $day => $cTimetable) {
            if ($cTimetable[0] != $timetable[$day]['start'] || $cTimetable[1] != $timetable[$day]['end']) {
                $tmp[$day] = [$timetable[$day]['start'], $timetable[$day]['end']];
            }
        }
        $user->setTimetable($tmp);
        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function setCreateUser(User $user): object
    {
        $user->setEmployedAtValue();
        $user->setRoles([$user::ROLE_STAFF]);
        $user->setTimetable([]);
        $this->manager->persist($user);
        $this->manager->flush();
        return $user;
    }

    public function setUpdateUser(User $user): object
    {
        $this->manager->persist($user);
        $this->manager->flush();
        return $user;
    }

    public function setDismissUser(User $user)
    {
        // TODO: Implement setDismissUser() method.
    }
}
