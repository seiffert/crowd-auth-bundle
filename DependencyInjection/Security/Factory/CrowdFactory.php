<?php

namespace Seiffert\CrowdAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class CrowdFactory extends FormLoginFactory
{
    /**
     * @param ContainerBuilder $container
     * @param string $id
     * @param array $config
     * @param string $userProviderId
     * @return string
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'security.authentication.provider.crowd.' . $id;

        $container->setDefinition($providerId, new DefinitionDecorator('ps.crowd.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProviderId));

        return $providerId;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'crowd-login';
    }
}
