<?php

namespace App\UserBundle\Manager;

use App\UserBundle\Repository\UserRepository;

class UserJsonManager implements EntityManagerInterface
{
    public function getRepository($className)
    {
        return new UserRepository($this, $className);
    }

    public function find($className, $id)
    {
        return $this->getRepository($className)->find($id);
    }
}