<?php


namespace App\Controller\Crm;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModalController extends AbstractController
{
    /**
     * @Route("/lk/modal/calendar-user-timetable/{user<\d+>}")
     */
    public function calendarUserTimetable(int $user): Response
    {
        if ($this->getUser()->getId() != $user) {
            return new JsonResponse([
                'code' => 403,
                'status' =>  'Forbidden',
                'response' => "Не авторизован"
            ]);
        }
        return new JsonResponse([
            'code' => 200,
            'status' =>  'Ok',
            'response' => "Ответ"
        ]);
    }
}