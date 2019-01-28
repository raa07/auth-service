<?php

namespace App\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;

/**
 * @RouteResource("User")
 */
class UsersController
{
    public function newAction()
    {
        return new View(['test'], 200);
    } // "new_users"     [GET] /users/new

    public function testAction()
    {
        return new View(['test auth'], 200);
    } // "test"     [GET] /users/test
}
