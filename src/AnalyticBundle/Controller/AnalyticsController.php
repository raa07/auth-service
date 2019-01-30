<?php

namespace App\AnalyticBundle\Controller;

use App\AnalyticBundle\Producer\DataSenderProducer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;

/**
 * @RouteResource("Analytics")
 */
class AnalyticsController
{
    private $producer;
    private $tokenEncoder;


    public function __construct(DataSenderProducer $dataSenderProducer, LcobucciJWTEncoder $tokenEncoder)
    {
        $this->producer = $dataSenderProducer;
        $this->tokenEncoder = $tokenEncoder;
    }

    public function saveDataAction(Request $request)
    {
        $token = '';
        if ($token) {
            $payload = $this->tokenEncoder->decode($token);
            $id_user = $payload['id'];
        } else {
            $id_user = 'uuuid';
        }

        $sourceLabel = $request->request->get('source_label');;

        $data = [
            'id' => uniqid('hit_', true),
            'id_user' => $id_user,
            'date' => date('Y-m-d H:i:s'),
            'source_label' => $sourceLabel
        ];

        $this->producer->sendData($data);

        return new View(['result' => 'done'], 200);
    }
}
