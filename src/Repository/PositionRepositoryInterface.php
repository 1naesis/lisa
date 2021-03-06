<?php


namespace App\Repository;


use App\Entity\Position;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

interface PositionRepositoryInterface
{
    /**
     * @return Position[]
     */
    public function getAllPosition():array;
    /**
     *
     * @param int $company_id
     * @return Position[]
     */
    public function getPositionByCompany(int $company_id):array;

    /**
     * @param int $position_id
     * @return object|null
     */
    public function getPosition(int $position_id):?object;

    /**
     * @param Position $position
     * @return Position
     */
    public function insertFormPosition(Position $position):object;

    /**
     * @param Position $position
     */
    public function setDismissPosition(Position $position);
}