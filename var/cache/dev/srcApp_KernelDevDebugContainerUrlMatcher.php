<?php

use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherTrait;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class srcApp_KernelDevDebugContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    use CompiledUrlMatcherTrait;

    public function __construct(RequestContext $context)
    {
        $this->context = $context;
        $this->staticRoutes = [
            '/test' => [[['_route' => 'index', '_controller' => 'App\\AnalyticsBundle\\Producer\\DataSenderProducer::index'], null, null, null, false, false, null]],
        ];
    }
}
