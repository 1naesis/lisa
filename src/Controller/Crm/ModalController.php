<?php


namespace App\Controller\Crm;


use App\Entity\User;
use App\Repository\ManualTimetableRepository;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModalController extends BasicController
{
    /**
     * @Route("/lk/modal/calendar-user-timetable")
     */
    public function calendarUserTimetable(Request $request, ManualTimetableRepository $manualTimetableRepository): Response
    {
        $user_id = $request->request->get('user_id');
        $date = new \DateTimeImmutable($request->request->get('date'));
        if (!$user_id || !$date) {
            return new JsonResponse([
                'code' => 400,
                'status' =>  'Bad Request',
                'response' => "Не авторизован"
            ]);
        }
//        if ($this->getUser()->getId() != $user_id) {
//            return new JsonResponse([
//                'code' => 403,
//                'status' =>  'Forbidden',
//                'response' => "Не авторизован"
//            ]);
//        }

        $days = [];
        $firstDayInWeek = (new \DateTimeImmutable())->setISODate($date->format('Y'), $date->format('W'));
        $firstDayInWeek = $firstDayInWeek->setTime(0,0);

        $days[] = [
            'title' => 'Пн',
            'date' => $firstDayInWeek->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek,
                    $user_id
                )
        ];
        $days[] = [
            'title' => 'Вт',
            'date' => $firstDayInWeek->modify('+1 day')->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek->modify('+1 day'),
                    $user_id
                )
        ];
        $days[] = [
            'title' => 'Ср',
            'date' => $firstDayInWeek->modify('+2 day')->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek->modify('+2 day'),
                    $user_id
                )
        ];
        $days[] = [
            'title' => 'Чт',
            'date' => $firstDayInWeek->modify('+3 day')->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek->modify('+3 day'),
                    $user_id
                )
        ];
        $days[] = [
            'title' => 'Пт',
            'date' => $firstDayInWeek->modify('+4 day')->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek->modify('+4 day'),
                    $user_id
                )
        ];
        $days[] = [
            'title' => 'Сб',
            'date' => $firstDayInWeek->modify('+5 day')->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek->modify('+5 day'),
                    $user_id
                )
        ];
        $days[] = [
            'title' => 'Вс',
            'date' => $firstDayInWeek->modify('+6 day')->format('Y-m-d'),
            'timetable' => $manualTimetableRepository->findManualTimetableByDateAndUser(
                    $firstDayInWeek->modify('+6 day'),
                    $user_id
                )
        ];
//        print_r($days);

        return new JsonResponse([
            'code' => 200,
            'status' =>  'Ok',
            'response' => $this->renderView('crm/setting/staff/modal/timetable.one.day.html.twig', [
                'days' => $days,
            ])
        ]);
    }

    /**
     * @Route("/lk/modal/edit-one-hour")
     */
    public function editOneHour (
            UserRepositoryInterface $userRepository,
            Request $request,
            ManualTimetableRepository $manualTimetableRepository
        )
        {
        $user = $userRepository->getUser($request->request->get('user_id'));
        if (!$user) {
            return new JsonResponse([
                'code' => 403,
                'status' =>  'Forbidden',
                'response' => "Не авторизован"
            ]);
        }

        $data = [
            'date' => new \DateTimeImmutable($request->request->get('date')),
            'time' => $request->request->get('time'),
            'user_id' => $user->getId(),
            'company_id' => $user->getCompanyId()
        ];
        try {
            $response = $manualTimetableRepository->changeManualTimetable($data);
        } catch (\Exception $exception) {

            return new JsonResponse([
                'code' => 500,
                'status' =>  'Internal Server Error',
                'response' => ''
            ]);
        }

        return new JsonResponse([
            'code' => 200,
            'status' =>  'Ok',
            'response' => $response
        ]);
    }
}