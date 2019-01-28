<?php

namespace App\UserBundle\Manager;

interface EntityManagerInterface
{
    public function find($className, $id);

    public function getRepository($className);
}