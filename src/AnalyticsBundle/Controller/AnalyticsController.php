<?php

namespace App\UserBundle\Controller;

use App\AnalyticsBundle\Producer\DataSenderProducer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use App\UserBundle\Security\JwtTokenAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @RouteResource("Analytics")
 */
class AnalyticsController
{
    private const UNIQ_ID_COOKIE_KEY = 'uniq_id';

    private $producer;
    private $authenticator;
    private $response;

    public function __construct(DataSenderProducer $dataSenderProducer, JwtTokenAuthenticator $authenticator)
    {
        $this->producer = $dataSenderProducer;
        $this->authenticator = $authenticator;
        $this->response = new JsonResponse();
    }

    public function saveDataAction(Request $request)
    {

        $sourceLabel = $request->request->get('source_label');

        try {
            $user = $this->authenticator->getUser($this->authenticator->getCredentials($request));
            $id_user = $user->getId();
        } catch (CustomUserMessageAuthenticationException $exception ) {
            $id_user = $this->setUniqId();
        }

        $data = [
            'id' => uniqid('hit_', true),
            'id_user' => $id_user,
            'date' => date('Y-m-d H:i:s'),
            'source_label' => $sourceLabel
        ];

        $this->producer->sendData($data);

        $this->response->setData(['result' => 'done']);
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
