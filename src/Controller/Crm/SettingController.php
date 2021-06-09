<?php

namespace App\Controller\Crm;

use App\Entity\Company;
use App\Entity\Position;
use App\Entity\User;
use App\Form\PositionType;
use App\Form\UserType;
use App\Repository\PositionRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{
    /**
     * @Route("/lk/setting", name="lk/setting")
     */
    public function index(): Response
    {
        return $this->render('crm/setting/index.html.twig', []);
    }

    /**
     *
     * STAFF
     *
     */

    /**
     * @Route("/lk/setting/company", name="lk/setting/company")
     */
    public function company(Request $request):Response
    {
        $company = new Company();
        $company_input = $request->request->get('company');

        if ($company_input) {
            //Валидация и сохранения данных в таблицу Компании
        }
        return $this->render('crm/setting/company/index.html.twig', [
            'company_input' => $company_input
        ]);
    }

    /**
     *
     * STAFF
     *
     */

    /**
     * @Route("/lk/setting/staff_timetable/{staff<\d+>}", name="lk/setting/staff_timetable")
     */
    public function staffTimeEdit(int $staff, UserRepositoryInterface $user_repository):Response
    {
        $user = new User();
        if($staff){
            $user = $user_repository->getUser($staff);
        }

        return $this->render('crm/setting/staff/edit_timetable.html.twig', [
            'staff' => $staff,
            'user' => $user
        ]);
    }

    /**
     * @Route("/lk/setting/staff/{staff<\d+>}", name="lk/setting/staff_edit")
     */
    public function staffEdit(int $staff, Request $request, UserRepositoryInterface $user_repository):Response
    {
        $user = new User();
        if($staff){
            $user = $user_repository->getUser($staff);
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user_repository->insertFormUser($user, $form);
            return $this->redirectToRoute('lk/setting/staff');
        }

        return $this->render('crm/setting/staff/edit.html.twig', [
            'staff' => $staff,
            'form_user' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/lk/setting/staff", name="lk/setting/staff")
     */
    public function staff(UserRepositoryInterface $user):Response
    {
        $users = $user->getAllUser();
        return $this->render('crm/setting/staff/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     *
     * POSITION
     *
     */

    /**
     * @Route("/lk/setting/position/{position_id<\d+>}", name="lk/setting/position_edit")
     */
    public function positionEdit(int $position_id, Request $request, PositionRepositoryInterface $position_repository):Response
    {
        $position = new Position();
        if($position_id){
            $position = $position_repository->getPosition($position_id);
        }
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $position_repository->insertFormPosition($position);
            return $this->redirectToRoute('lk/setting/position');
        }
        return $this->render('crm/setting/position/edit.html.twig', [
            'form_position' => $form->createView(),
            'position_id' => $position_id,
            'position' => $position
        ]);
    }

    /**
     * @Route("/lk/setting/position", name="lk/setting/position")
     */
    public function position(PositionRepositoryInterface $position_repository):Response
    {
        $positions = $position_repository->getAllPosition();
        return $this->render('crm/setting/position/index.html.twig', [
            'positions' => $positions
        ]);
    }
}
