<?php

namespace App\UserBundle\Repository;

use App\UserBundle\Entity\User;
use App\UserBundle\Manager\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository implements ObjectRepository
{
    private $em;
    private $fileSys;
    private $usersDir;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em,
                                Filesystem $fileSys,
                                string $usersDir,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->fileSys = $fileSys;
        $this->usersDir = $usersDir;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function find($id)
    {
        return $this->getFromStorage($id);
    }

    public function loadUserByUsername(string $nickname)
    {
        $id = $this->nicknameToId($nickname);
        return $this->getFromStorage($id);
    }

    public function save(User $user) : string
    {
        $nickname = $user->getNickname();
        $id = $this->nicknameToId($nickname);
        if (empty($user->getPassword())) {
            throw new \Exception('Empty password');
        }
        if ($this->getFromStorage($id)) {
            throw new \Exception('Nickname is not uniq');
        }
        $data = $user->toArray();
        $this->saveToStorage($id, $data);
        return $id;
    }

    public function encodePassword(User $user, string $password) : User
    {
        $encoded = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        return $user;
    }

    public function nicknameToId(string $nickname) : string
    {
        return md5($nickname);
    }

    private function getFromStorage(string $id) : array
    {
        $path = $this->usersDir . '/' . $id . '.json';
        if (!$this->fileSys->exists($path)) {
            return [];
        }
        $data = file_get_contents($path);

        return (array) json_decode($data, true);
    }

    private function saveToStorage(string $id, array $data) : bool
    {
        $json = json_encode($data);
        $path = $this->usersDir . '/' . $id . '.json';
        $this->fileSys->mkdir($this->usersDir, 0700);

        return (bool) file_put_contents($path, $json);
    }

    public function findAll()
    {
    }

    public function findBy(array $criteria)
    {
    }

    public function findOneBy(array $criteria)
    {
    }
}