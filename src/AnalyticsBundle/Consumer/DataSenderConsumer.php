<?php

namespace App\AnalyticsBundle\Consumer;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
/**
 * Class NotificationConsumer
 */
class DataSenderConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        file_put_contents('test.txt', $msg->getBody());
    }
}
