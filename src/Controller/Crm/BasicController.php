<?php


namespace App\Controller\Crm;


use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class BasicController extends AbstractController
{
    private ?UserInterface $user;
    private ?Company $company;
    private CompanyRepository $companyRepository;
    public Security $security;

    public function __construct(CompanyRepository $companyRepository, Security $security)
    {
        $this->security = $security;
        $this->companyRepository = $companyRepository;
    }

    protected function getThisUser(): ?UserInterface
    {
        if (!isset($this->user)) {
            $user = $this->getUser();
            if (!$user) {
                throw new \Exception('Not authorized');
            }
            $this->user = $user;
        }
        return $this->user??null;
    }

    protected function getThisCompany(): ?Company
    {
        if (!isset($this->company)) {
            $user = $this->getThisUser();
            if ($user) {
                $this->company = $this->companyRepository->getCompanyByUser($user->getId());
            }
        }
        return $this->company;
    }
}