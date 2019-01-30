<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\RouteResource;
/**
 * @RouteResource("User")
 */
class UsersController
{
    private $userRepo;
    public $userProvider;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function newAction(Request $request)
    {
        $user = new User();
        $user->create('test', 'test_test', 'test', 'test', 10);
        $user = $this->userRepo->encodePassword($user, 'test');
        $this->userRepo->save($user);
        return new View(['success' => 'true', 'data' => ['message' => 'User created']], 200);
    } // "new_users"     [GET] /users/new
}
