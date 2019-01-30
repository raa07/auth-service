<?php

namespace App\AnalyticBundle\Controller;

use App\AnalyticBundle\Producer\DataSenderProducer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use App\UserBundle\Security\JwtTokenAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;

/**
 * @RouteResource("Analytics")
 */
class AnalyticsController
{
    private const UNIQ_ID_COOKIE_KEY = 'uniq_id';

    private $producer;
    private $response;
    private $tokenEncoder;

    public function __construct(DataSenderProducer $dataSenderProducer, LcobucciJWTEncoder $tokenEncoder)
    {
        $this->producer = $dataSenderProducer;
        $this->response = new JsonResponse();
        $this->tokenEncoder = $tokenEncoder;
    }

    public function saveDataAction(Request $request)
    {
        $token = '';
        if ($token) {
            $payload = $this->tokenEncoder->decode($token);
            $id_user = $payload['id'];
        } else {
            $id_user = $this->setUniqId();
        }

        $sourceLabel = $request->request->get('source_label');;

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
