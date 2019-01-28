<?php

namespace App\UserBundle\Repository;


interface ObjectRepository
{
    public function find($id);

    public function findAll();

    public function findBy(array $criteria);

    public function findOneBy(array $criteria);
}