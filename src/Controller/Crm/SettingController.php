<?php

namespace App\Controller\Crm;

use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Position;
use App\Entity\Service;
use App\Entity\User;
use App\Form\PositionType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\CompanyRepository;
use App\Repository\ManualTimetableRepository;
use App\Repository\PositionRepositoryInterface;
use App\Repository\ServiceRepository;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends BasicController
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
     * SERVICES
     *
     */



    /**
     * @Route("/lk/setting/services/{service_id<\d+>}", name="lk/setting/service_edit")
     */
    public function editServices(
        int $service_id,
        Request $request,
        ServiceRepository $serviceRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        $company = $this->getThisCompany();
        if ($service_id) {
            $service = $serviceRepository->getService($service_id);
            if (!$service) {
                return $this->redirectToRoute('lk/setting/services');
            }
        } else {
            $service = new Service();
        }
        $categories = $categoryRepository->getCategoriesByCompany($company->getId());

        $data = $request->request->get("service");
        if ($data && $serviceRepository->validation($data)) {
            $data['company_id'] = (int)$company->getId();
            $serviceRepository->setData($service, $data);
            $serviceRepository->insertService($service);
            return $this->redirectToRoute('lk/setting/services');
        }
        return $this->render('crm/setting/service/edit.html.twig', [
            'data' => $data,
            'service' => $service,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/lk/setting/services", name="lk/setting/services")
     */
    public function services(Request $request, ServiceRepository $serviceRepository): Response
    {
        $category = $request->get('category')??0;
        $company = $this->getThisCompany();
        if ($category > 0) {
            $services = $serviceRepository->getServicesByCompanyAndCatalog($company->getId(), $category);
        } else {
            $services = $serviceRepository->getServicesByCompany($company->getId());
        }
        return $this->render('crm/setting/service/index.html.twig', [
            'services' => $services
        ]);
    }

    /**
     * @Route("/lk/setting/category-services/{category_id<\d+>}", name="lk/setting/category_edit")
     */
    public function editCategoriesServices(int $category_id, Request $request, CategoryRepository $categoryRepository): Response
    {
        $company = $this->getThisCompany();
        if ($category_id) {
            $category = $categoryRepository->getCategory($category_id);
            if (!$category) {
                return $this->redirectToRoute('lk/setting/category-services');
            }
        } else {
            $category = new Category();
        }

        $data = $request->request->get("category");
        if ($data && $categoryRepository->validation($data)) {
            $data['company_id'] = (int)$company->getId();
            $categoryRepository->setData($category, $data);
            $categoryRepository->insertCategory($category);
            return $this->redirectToRoute('lk/setting/category-services');
        }
        return $this->render('crm/setting/category/edit.html.twig', [
            'data' => $data,
            'category' => $category
        ]);
    }

    /**
     * @Route("/lk/setting/category-services", name="lk/setting/category-services")
     */
    public function categoriesServices(CategoryRepository $categoryRepository): Response
    {
        $company = $this->getThisCompany();
        $categories = $categoryRepository->getCategoriesByCompany($company->getId());
        return $this->render('crm/setting/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     *
     * COMPANY
     *
     */

    /**
     * @Route("/lk/setting/company", name="lk/setting/company")
     */
    public function company(Request $request, CompanyRepository $companyRepository):Response
    {
        if (!$this->security->isGranted(User::ROLE_ADMIN)) {
            return $this->redirectToRoute('lk/setting');
        }
        $user_this = $this->getThisUser();
        $company = $this->getThisCompany();
        if (!$company) {
            $company = new Company();
        }


        $company_input = $request->request->get('company');
        if ($company_input && $companyRepository->validation($company_input)) {
            $company_input['user'] = $user_this->getId();
            $companyRepository->setData($company, $company_input);
            $companyRepository->insertCompany($company);
        }
        return $this->render('crm/setting/company/index.html.twig', [
            'company_input' => $company_input,
            'company' => $company
        ]);
    }

    /**
     *
     * STAFF
     *
     */

    /**
     * @Route("/lk/setting/staff_timetable/{staff<\d+>?0}", name="lk/setting/staff_timetable")
     */
    public function staffTimeEdit
    (
        int $staff,
        UserRepositoryInterface $user_repository,
        CompanyRepository $companyRepository,
        Request $request
    ):Response
    {
        $timetable = $request->request->get('timetable');
        $user = $user_repository->getUser($staff);
        if (!$user || $user->getId() == 0) {
            return $this->redirectToRoute('lk/setting/staff');
        }
        $company = $companyRepository->getCompanyById($user->getCompanyId());
        if ($timetable) {
            $user_repository->setTimetable($user, $company, $timetable);
            return $this->redirectToRoute('lk/setting/staff');
        }
        return $this->render('crm/setting/staff/edit_timetable.html.twig', [
            'staff' => $staff,
            'user' => $user,
            'company' => $company,
            'timetable' => $timetable
        ]);
    }

    /**
     * @Route("/lk/setting/staff/{staff<\d+>}", name="lk/setting/staff_edit")
     */
    public function staffEdit(int $staff, Request $request, UserRepositoryInterface $user_repository):Response
    {
        $user_this = $this->getUser();

        $user = new User();
        if($staff){
            $user = $user_repository->getUser($staff);
            if (!$user) {
                return $this->redirectToRoute('lk/setting/staff');
            }
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setCompanyId($user_this->getCompanyId());
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
    public function staff(UserRepositoryInterface $userRepository, ManualTimetableRepository $manualTimetableRepository):Response
    {
        $user = $this->getUser();
        $company = $this->getThisCompany();
        if (!$user) {
            $user = new User();
        }
        $user->setCompanyTimetable($company);
        $users = $userRepository->getUserByCompany($user->getCompanyId());
        foreach ($users as &$user) {
            $manual = $manualTimetableRepository->
                findManualTimetableByDateAndUser(
                    (new \DateTimeImmutable())->setTime(0, 0),
                    $user->getId()
                );
            if ($manual) {
                $user->setManualTimetable($manual->getTimetable());
            }
        }
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
        $user_this = $this->getUser();
        $position = new Position();
        if($position_id){
            $position = $position_repository->getPosition($position_id);
            if (!$position) {
                return $this->redirectToRoute('lk/setting/position');
            }
        }
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $position->setCompanyId($user_this->getCompanyId());
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
        $user = $this->getUser();
        $company = $this->getThisCompany();
        $positions = $position_repository->getPositionByCompany($company->getId());
        return $this->render('crm/setting/position/index.html.twig', [
            'positions' => $positions
        ]);
    }
}
