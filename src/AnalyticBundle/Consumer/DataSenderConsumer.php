<?php

namespace App\AnalyticBundle\Consumer;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use SocialTech\StorageInterface;
/**
 * Class NotificationConsumer
 */
class DataSenderConsumer implements ConsumerInterface
{
    private $slowStorage;

    public function __construct(StorageInterface $slowStorage)
    {
        $this->slowStorage = $slowStorage;
    }

    public function execute(AMQPMessage $msg)
    {
        $json = $msg->getBody();
        $data = json_decode($json, true);
        $name = '/app/storage/analytic/' . $data['id'] . '.json'; //path fix TODO:refactor
        if ($this->slowStorage->exit($name)) {
            throw new \Exception('Duplicate');
        }
        $this->slowStorage->store($name, $json);
    }
}
