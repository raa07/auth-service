<?php

namespace App\AnalyticsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use OldSound\RabbitMqBundle\DependencyInjection\OldSoundRabbitMqExtension;
use OldSound\RabbitMqBundle\DependencyInjection\Compiler\RegisterPartsPass;

class AnalyticsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->registerExtension(new OldSoundRabbitMqExtension());
        $container->addCompilerPass(new RegisterPartsPass());
    }
}
