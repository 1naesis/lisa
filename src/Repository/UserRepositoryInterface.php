<?php


namespace App\Repository;


use App\Entity\Company;
use App\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAllUser():array;

    /**
     * @param int $company_id
     * @return User[]
     */
    public function getUserByCompany(int $company_id):array;

    /**
     * @param int $user_id
     * @return object|null
     */
    public function getUser(int $user_id):?object;

    /**
     * @param User $user
     * @param Form $form
     * @return User
     */
    public function insertFormUser(User $user, FormInterface $form):object;

    /**
     * @param User $user
     * @param Company $company
     * @param array $timetable
     */
    public function setTimetable(User $user, Company $company, array $timetable):void;

    /**
     * @param User $user
     * @return object
     */
    public function setCreateUser(User $user):object;
    /**
     * @param User $user
     * @return User
     */

    public function setUpdateUser(User $user):object;
    /**
     * @param User $user
     */
    public function setDismissUser(User $user);
}