<?php

namespace App\AnalyticBundle\Controller;

use App\AnalyticBundle\Producer\DataSenderProducer;
use App\UserBundle\Security\JwtTokenAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @RouteResource("Analytics")
 */
class AnalyticsController
{
    private const UNIQ_ID_COOKIE_KEY = 'uniq_id';

    private $producer;
    private $response;
    private $userProvider;
    private $tokenAuthenticator;

    public function __construct(DataSenderProducer $dataSenderProducer, UserProviderInterface $userProvider, JwtTokenAuthenticator $tokenAuthenticator)
    {
        $this->producer = $dataSenderProducer;
        $this->response = new JsonResponse();
        $this->userProvider = $userProvider;
        $this->tokenAuthenticator = $tokenAuthenticator;
    }

    public function newAction(Request $request)
    {

        $user = $this->tokenAuthenticator->getUserIfAuthenticated($request, $this->userProvider);
        if (!empty($user)){
            $id_user = $user->getUsername();
        } else {
            $id_user = $this->getUniqId($request);
        }

        $sourceLabel = $request->query->get('source_label');

        $data = [ //TODO: replace
            'id' => uniqid('hit_', true),
            'id_user' => $id_user,
            'date' => date('Y-m-d H:i:s'),
            'source_label' => $sourceLabel
        ];

        $this->producer->sendData($data);

        $this->response->setData(['success' => 'true', 'data' => ['message' => 'hit recovered']]);
        return $this->response;
    }

    public function getUniqId(Request $request) : string
    {
        $uniq_id =$request->cookies->get(self::UNIQ_ID_COOKIE_KEY, false);
        if (!$uniq_id) {
            $uniq_id = uniqid('',true);
        }
        $this->response->headers->setCookie(new Cookie(self::UNIQ_ID_COOKIE_KEY, $uniq_id));

        return $uniq_id;
    }
}
