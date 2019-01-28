<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use \Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController
{
    private $token_encoder;
    private $passEncoder;

    public function __construct(LcobucciJWTEncoder $token_encoder, UserPasswordEncoderInterface $password_encoder)
    {
        $this->token_encoder = $token_encoder;
        $this->passEncoder = $password_encoder;
    }

    public function login(Request $request): Response
    {
        $user = new User();

        if (!$user) {
            throw new Exception('Not found user with this creds');
        }
        $isValid = $this->passEncoder
            ->isPasswordValid($user, $request->getPassword());


        $token = $this->encoder->encode([
                'username' => $user->getNickname(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);
    }
}
