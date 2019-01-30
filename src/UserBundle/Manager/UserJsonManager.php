<?php

namespace App\UserBundle\Manager;

use App\UserBundle\Repository\UserRepository;
use Symfony\Component\Filesystem\Filesystem;

class UserJsonManager implements EntityManagerInterface
{
    private $usersDir;

    public function __construct(string $usersDir)
    {
        $this->usersDir = $usersDir;
    }

    public function getRepository($className)
    {
        $fileSystem = new Filesystem();
        return new UserRepository($this, $className, $fileSystem, $this->usersDir);
    }

    public function find($className, $id)
    {
        return $this->getRepository($className)->find($id);
    }
}