<?php

namespace App\UserBundle\Repository;

use App\UserBundle\Entity\User;
use App\UserBundle\Manager\EntityManagerInterface;

class UserRepository implements ObjectRepository
{
    private $em;
    private $class;

    public function __construct(EntityManagerInterface $em, string $class)
    {
        $this->em         = $em;
        $this->class      = $class;
    }

    public function find($id)
    {

        // TODO: Implement find() method.
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

    public function save(User $user)
    {

    }

    private function getFromStorage(string $id)
    {

    }
}