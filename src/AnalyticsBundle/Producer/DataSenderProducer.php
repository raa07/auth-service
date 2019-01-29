<?php

namespace App\AnalyticsBundle\Producer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\HttpFoundation\Response;

class DataSenderProducer extends AbstractController
{
    private $producer;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    public function sendData(array $data) : void
    {
        $json = json_encode($data);
        $this->producer->publish($json);
    }
}
