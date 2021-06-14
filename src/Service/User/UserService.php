<?php


namespace App\Service\User;


use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword);
        $user->setPassword($password);
    }
}