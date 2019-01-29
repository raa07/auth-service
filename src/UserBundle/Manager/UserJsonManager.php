<?php

namespace App\UserBundle\Manager;

use App\UserBundle\Repository\UserRepository;
use Symfony\Component\Filesystem\Filesystem;

class UserJsonManager implements EntityManagerInterface
{
    public function getRepository($className)
    {
        $fileSystem = new Filesystem();
        return new UserRepository($this, $className, $fileSystem);
    }

    public function find($className, $id)
    {
        return $this->getRepository($className)->find($id);
    }
}