<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Security\UserProvider;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use \Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Exception as FosException;
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

//    /**
//     * @Post()
//     */
    public function cgetAction(Request $request)
    {
        $nickname = $request->query->get('nickname', '');
        $password = $request->query->get('password', '');

        try {
            $user = $this->userProvider->loadUserByUsername($nickname);
        } catch (Exception $exception) {
            throw new FosException('Not found user with this creds');
        }
        $isValid = $this->passEncoder
            ->isPasswordValid($user, $password);
        if (!$isValid) {
            throw new FosException('Password is wrong');
        }

        $token = $this->tokenEncoder->encode([ // TODO: replace
            'nickname' => $user->getNickname(),
            'id' => $user->getId(),
            'exp' => time() + 36000 // 10 hour expiration
        ]);

        return new View(['success' => 'true', 'data' => ['token' => $token]], 200);
    }
}
