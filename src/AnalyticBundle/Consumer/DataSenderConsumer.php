<?php

namespace App\AnalyticBundle\Consumer;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use SocialTech\StorageInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class NotificationConsumer
 */
class DataSenderConsumer implements ConsumerInterface
{
    private $slowStorage;
    private $analyticsDir;
    private $fileSys;

    public function __construct(StorageInterface $slowStorage,Filesystem $fileSys, string $analyticsDir)
    {
        $this->slowStorage = $slowStorage;
        $this->analyticsDir = $analyticsDir;
        $this->fileSys = $fileSys;
    }

    public function execute(AMQPMessage $msg)
    {
        $json = $msg->getBody();
        $data = json_decode($json, true);
        $name = $this->analyticsDir . '/' . $data['id'] . '.json';
        $this->fileSys->mkdir($this->analyticsDir, 0700);

        if ($this->slowStorage->exists($name)) {
            throw new \Exception('Duplicate');
        }
        $this->slowStorage->store($name, $json);
    }
}
