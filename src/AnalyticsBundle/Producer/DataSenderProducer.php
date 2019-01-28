<?php

namespace App\AnalyticsBundle\Producer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NotificationConsumer
 */
class DataSenderProducer extends AbstractController
{
    public function index(ProducerInterface $producer)
    {
        $producer->publish('asdfasdfasdfasdf');
        return new Response('asdfasdf');
    }
}
