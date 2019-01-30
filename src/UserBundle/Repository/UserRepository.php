<?php

namespace App\UserBundle\Repository;

use App\UserBundle\Entity\User;
use App\UserBundle\Manager\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class UserRepository implements ObjectRepository
{
    private $em;
    private $fileSys;
    private $usersDir;

    public function __construct(EntityManagerInterface $em, Filesystem $fileSys, $usersDir)
    {
        $this->em         = $em;
        $this->fileSys = $fileSys;
        $this->usersDir = $usersDir;
    }

    public function find($id)
    {
        return $this->getFromStorage($id);
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    public function findBy(array $criteria)
    {
        // TODO: Implement findBy() method.
    }

    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
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
        if ($this->getFromStorage($id)) {
            throw new \Exception('Nickname is not uniq');
        }
        $data = $user->toArray();
        $this->saveToStorage($id, $data);
        return $id;
    }

    private function getFromStorage(string $id) : array
    {
        $path = $this->usersDir . $id . '.json';
        if (!$this->fileSys->exists($path)) {
            return [];
        }
        $data = file_get_contents($path);
        return (array) json_decode($data, true);
    }

    private function saveToStorage(string $id, array $data) : bool
    {
        $json = json_encode($data);
        $path = $this->usersDir . $id . '.json';
        $this->fileSys->mkdir('/app/storage/users', 0700);
        return (bool) file_put_contents($path, $json);
    }

    private function nicknameToId(string $nickname) : string
    {
        return md5($nickname);
    }
}