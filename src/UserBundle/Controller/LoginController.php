<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Security\UserProvider;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use \Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use App\UserBundle\Security\JwtTokenAuthenticator;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 * @RouteResource("Login", pluralize=false)
 * @Rest\Prefix("api")
 */
class LoginController
{
    private $tokenAuthenticator;
    private $passEncoder;
    private $userProvider;

    public function __construct(JwtTokenAuthenticator $tokenAuthenticator,
                                UserPasswordEncoderInterface $passwordEncoder,
                                UserProvider $userProvider)
    {
        $this->tokenAuthenticator = $tokenAuthenticator;
        $this->passEncoder = $passwordEncoder;
        $this->userProvider = $userProvider;
    }

    /**
     * @Post()
     */
    public function postAuthAction(Request $request)
    {
        $nickname = $request->request->get('nickname', '');
        $password = $request->request->get('password', '');

        try {
            $user = $this->userProvider->loadUserByUsername($nickname);
        } catch (Exception $exception) {
            return new View(['success' => 'false', 'message' => 'Not found user with this creds'], 401);
        }

        $isValid = $this->passEncoder
            ->isPasswordValid($user, $password);
        if (!$isValid) {
            return new View(['success' => 'false', 'message' => 'Password is wrong'], 401);
        }

        $token = $this->tokenAuthenticator->tokenGenerate($user);

        return new View(['success' => 'true', 'data' => ['token' => $token]], 200);
    }// "post_auth" [POST] /login/auth
}
