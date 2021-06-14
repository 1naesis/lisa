<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
 */
class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_STAFF = 'ROLE_STAFF';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Position")
     *
     */
    private $position;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $employed_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fired_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $company_id;

    /**
     * @ORM\Column(type="json")
     */
    private $timetable = [];

    private static ?array $companyTimetable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmployedAt(): ?\DateTimeInterface
    {
        return $this->employed_at;
    }

    public function setEmployedAtValue(): void
    {
        $this->employed_at = new \DateTime();
    }

    public function setEmployedAt(?\DateTimeInterface $employed_at): self
    {
        $this->employed_at = $employed_at;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getFiredAt(): ?\DateTimeInterface
    {
        return $this->fired_at;
    }

    public function setFiredAt(?\DateTimeInterface $fired_at): self
    {
        $this->fired_at = $fired_at;

        return $this;
    }

    public function __call(string $name, array $arguments):string
    {
        return $this->getUsername();
    }

    public function getCompanyId(): ?int
    {
        return $this->company_id;
    }

    public function setCompanyId(int $company_id): self
    {
        $this->company_id = $company_id;

        return $this;
    }

    public function getTimetable(): ?array
    {
        return $this->timetable;
    }

    public function setTimetable(array $timetable): self
    {
        $this->timetable = $timetable;

        return $this;
    }

    public  function setCompanyTimetable(Company $company):void
    {
        self::$companyTimetable = $company->getTimetable();
    }
    public function getWorkStatus()
    {
        if (!isset(self::$companyTimetable)) {
            throw new \Exception("В модель не загружено расписание компании");
        }
        $nowTime = new \DateTimeImmutable();
        list($nowW, $nowH, $nowM) = explode('-', $nowTime->format('w-G-i'));
        $userTimetable = $this->getTimetable();
        if (isset($userTimetable[$nowW])) {
            list($start, $end) = $userTimetable[$nowW];
        } else {
            list($start, $end) = self::$companyTimetable[$nowW];
        }
        if ($start == $end) {
            return 0;
        }
        list($hStart, $mStart) = explode('-', $start);
        list($hEnd, $mEnd) = explode('-', $end);
        if ($hStart <= $nowH && $nowH <= $hEnd) {
            if (
                ($hStart == $nowH && $nowM < $mStart) &&
                ($hEnd == $nowH && $nowM >= $mEnd)
            ) {
                return 0;
            }
            return 1;
        }
        return 0;
    }
}
