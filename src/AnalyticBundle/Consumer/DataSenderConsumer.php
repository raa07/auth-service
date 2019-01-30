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
    private $analyticsDir;

    public function __construct(StorageInterface $slowStorage, string $analyticsDir)
    {
        $this->slowStorage = $slowStorage;
        $this->analyticsDir = $analyticsDir;
    }

    public function execute(AMQPMessage $msg)
    {
        $json = $msg->getBody();
        $data = json_decode($json, true);
        $name = $this->analyticsDir . $data['id'] . '.json'; //path fix
        if ($this->slowStorage->exit($name)) {
            throw new \Exception('Duplicate');
        }
        $this->slowStorage->store($name, $json);
    }
}
