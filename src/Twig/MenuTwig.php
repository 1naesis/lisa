<?php


namespace App\Twig;


use App\Entity\User;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\TwigFunction;

class MenuTwig extends \Twig\Extension\AbstractExtension
{
    private $userRepository;
    /**
     * MenuTwig constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getAllStaff', [$this, 'getStaff']),
            new TwigFunction('getTime', [$this, 'getTime']),
        ];
    }

    public function getTime(string $template = null): string
    {
        $month = [
            1 => "января",
            2 => "февраля",
            3 => "марта",
            4 => "апреля",
            5 => "мая",
            6 => "июня",
            7 => "июля",
            8 => "августа",
            9 => "сентября",
            10 => "октября",
            11 => "ноября",
            12 => "декабря"
        ];
        if ($template !== null) {
            $date = (new \DateTimeImmutable("now"))->format($template);
        } else {
//            8 Апрель 2021 18:50
            $date = new \DateTimeImmutable("now");
            $date = $date->format('d') . ' ' . $month[$date->format('j')] . ' ' . $date->format('Y H:i');
        }
        return $date;
    }

    public function getStaff(): ?array
    {

//        $userRepository = new UserRepository();
        $users = $this->userRepository->getUserByCompany(1);
        return $users??null;
    }

}