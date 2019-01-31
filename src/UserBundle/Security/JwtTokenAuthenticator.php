<?php

namespace App\UserBundle\Security;

use App\UserBundle\Entity\User;
use App\UserBundle\Manager\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $jwtEncoder;
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager, JWTEncoderInterface $jwtEncoder, EntityManagerInterface $em)
    {
        $this->jwtManager = $jwtManager;
        $this->em = $em;
        $this->jwtEncoder = $jwtEncoder;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request)
    {
        $jsonWebToken = $request->headers->get('Authorization');
        $preAuthToken = new PreAuthenticationJWTUserToken($jsonWebToken);
        try {
            var_dump($this->jwtManager->decode($preAuthToken));
            die();
            if (!$payload = $this->jwtManager->decode($preAuthToken)) {
                throw new InvalidTokenException('Invalid JWT Token');
            }
            $preAuthToken->setPayload($payload);
        } catch (JWTDecodeFailureException $e) {
            if (JWTDecodeFailureException::EXPIRED_TOKEN === $e->getReason()) {
                throw new ExpiredTokenException();
            }
            throw new InvalidTokenException('Invalid JWT Token', 0, $e);
        }
        return $preAuthToken;

    }

    public function getUser($preAuthToken, UserProviderInterface $userProvider)
    {
        $data = $preAuthToken->getPayload();

        $nickname = $data['nickname'];
        return $userProvider->loadUserByUsername($nickname);
    }

    public function tokenGenerate(User $user)
    {
        $token = $this->jwtEncoder->encode([
            'nickname' => $user->getNickname(),
            'id' => $user->getId(),
            'exp' => time() + 36000 // 10 hour expiration
        ]);
        return $token;
    }

    public function getUserIfAuthenticated(Request $request, UserProviderInterface $userProvider)
    {
        try {
            $creads = $this->getCredentials($request);
            return $this->getUser($creads, $userProvider);
        } catch (\Exception $exception) {
            return [];
        }
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}