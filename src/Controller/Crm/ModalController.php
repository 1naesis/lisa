<?php


namespace App\Controller\Crm;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModalController extends AbstractController
{
    /**
     * @Route("/lk/modal/calendar-user-timetable")
     */
    public function calendarUserTimetable(Request $request): Response
    {
        $user_id = $request->request->get('user_id');
        $date = $request->request->get('date');
        if (!$user_id || !$date) {
            return new JsonResponse([
                'code' => 400,
                'status' =>  'Bad Request',
                'response' => "Не авторизован"
            ]);
        }
        if ($this->getUser()->getId() != $user_id) {
            return new JsonResponse([
                'code' => 403,
                'status' =>  'Forbidden',
                'response' => "Не авторизован"
            ]);
        }

//        $date = new \DateTimeImmutable($date);
//        print_r($date);
//        echo PHP_EOL;
//        return new Response("test");


        return new JsonResponse([
            'code' => 200,
            'status' =>  'Ok',
            'response' => $this->renderView('crm/setting/staff/modal/timetable.one.day.html.twig', [])
        ]);
    }
}