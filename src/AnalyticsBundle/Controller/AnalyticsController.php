<?php

namespace App\UserBundle\Controller;

use App\AnalyticsBundle\Producer\DataSenderProducer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;

/**
 * @RouteResource("Analytics")
 */
class AnalyticsController
{
    private $producer;

    public function __construct(DataSenderProducer $dataSenderProducer)
    {
        $this->producer = $dataSenderProducer;
    }

    public function saveDataAction(Request $request)
    {
        $sourceLabel = $request->request->get('source_label');;

        $data = [
            'id' => uniqid('hit_', true),
            'id_user' => '',
            'date' => date('Y-m-d H:i:s'),
            'source_label' => $sourceLabel
        ];

        $this->producer->sendData($data);

        return new View(['result' => 'done'], 200);
    }
}
