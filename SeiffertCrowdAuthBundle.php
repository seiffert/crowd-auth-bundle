<?php

namespace Seiffert\CrowdAuthBundle;

use Seiffert\CrowdAuthBundle\DependencyInjection\Security\Factory\CrowdFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SeiffertCrowdAuthBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new CrowdFactory());
    }
}
