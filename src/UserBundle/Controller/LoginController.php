<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use App\UserBundle\Security\UserProvider;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use \Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Login", pluralize=false)
 */
class LoginController
{
    private $tokenEncoder;
    private $passEncoder;
    private $userProvider;

    public function __construct(LcobucciJWTEncoder $tokenEncoder, UserPasswordEncoderInterface $passwordEncoder, UserProvider $userProvider)
    {
        $this->tokenEncoder = $tokenEncoder;
        $this->passEncoder = $passwordEncoder;
        $this->userProvider = $userProvider;
    }

    public function cgetAction(Request $request): Response
    {
        $nickname = 'test';
        $user = $this->userProvider->loadUserByUsername($nickname);
        if (!$user) {
            throw new Exception('Not found user with this creds');
        }
        $isValid = $this->passEncoder
            ->isPasswordValid($user, $request->getPassword());


        $token = $this->tokenEncoder->encode([
            'nickname' => $user->getNickname(),
            'id' => $user->getId(),
            'exp' => time() + 36000 // 10 hour expiration
        ]);

        return new View(['token' => $token], 200); //TODO:refactor
    }
}
