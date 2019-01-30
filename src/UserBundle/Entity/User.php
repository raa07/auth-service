<?php

namespace App\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class User implements UserInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $nickname;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $firstName;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $lastName;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $age;

    /**
     * @Assert\UserPassword
     * @Assert\Length(min=3)
     */
    private $password;

    private $roles = [];

    public function getId() : string
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $last_name): self
    {
        $this->lastName = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $first_name): self
    {
        $this->firstName = $first_name;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->nickname;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'lastname' => $this->lastName,
            'firstname' => $this->firstName,
            'age' => $this->age,
            'password' => $this->password
        ];
    }

    // не хочу использовать никакой магии в виде динамическх аргументов и тд
    public function create(string $id, string $nickname, string $firstName, string $lastName, int $age, string $password='') : User
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->password = $password;

        return $this;
    }
}
