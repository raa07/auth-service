<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * @RouteResource("User")
 * @Rest\Prefix("api")
 */
class UsersController
{
    private $userRepo;
    private $validator;

    public function __construct(UserRepository $userRepo, ValidatorInterface $validator)
    {
        $this->userRepo = $userRepo;
        $this->validator = $validator;
    }

    /**
     * @Post()
     */
    public function newAction(Request $request)
    {
        $nickname = $request->request->get('nickname', '');
        $lastName = $request->request->get('last_name', '');
        $firstName = $request->request->get('first_name', '');
        $age = (int) $request->request->get('age', 0);
        $password = $request->request->get('password', '');

        try {
            $user = new User();
            $id = $this->userRepo->nicknameToId($nickname);
            $user->create($id, $nickname, $firstName, $lastName, $age);
            $user = $this->userRepo->encodePassword($user, $password);
        } catch (\Exception $exception) {
            return new View(['success' => 'true', 'message' => $exception->getMessage()], 401);
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return new View(['success' => 'false', 'message' => $errorsString], 401);
        }

        try {
            $this->userRepo->save($user);
        } catch (\Exception $exception) {
            return new View(['success' => 'true', 'message' => $exception->getMessage()], 401);
        }

        return new View(['success' => 'true', 'data' => ['message' => 'User created']], 200);
    } // "new_users"     [POST] /users/new
}
