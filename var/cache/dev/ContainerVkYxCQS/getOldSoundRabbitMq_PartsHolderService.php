<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'old_sound_rabbit_mq.parts_holder' shared service.

include_once $this->targetDirs[3].'/vendor/php-amqplib/rabbitmq-bundle/RabbitMq/AmqpPartsHolder.php';

$this->services['old_sound_rabbit_mq.parts_holder'] = $instance = new \OldSound\RabbitMqBundle\RabbitMq\AmqpPartsHolder();

$a = ($this->services['old_sound_rabbit_mq.send_email_producer'] ?? $this->load('getOldSoundRabbitMq_SendEmailProducerService.php'));
$b = ($this->services['old_sound_rabbit_mq.send_email_consumer'] ?? $this->load('getOldSoundRabbitMq_SendEmailConsumerService.php'));

$instance->addPart('old_sound_rabbit_mq.base_amqp', $a);
$instance->addPart('old_sound_rabbit_mq.base_amqp', $b);
$instance->addPart('old_sound_rabbit_mq.producer', $a);
$instance->addPart('old_sound_rabbit_mq.consumer', $b);
$instance->addPart('old_sound_rabbit_mq.base_amqp', $a);
$instance->addPart('old_sound_rabbit_mq.base_amqp', $b);
$instance->addPart('old_sound_rabbit_mq.producer', $a);
$instance->addPart('old_sound_rabbit_mq.consumer', $b);

return $instance;