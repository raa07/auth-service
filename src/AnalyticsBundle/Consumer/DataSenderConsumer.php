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
        echo $msg->getBody();
        file_put_contents('/app/storage/test.txt', 'asdfasdfasd');
    }
}
