<?php

namespace Modelizer\Enfield\Extensions;

use Behat\MinkExtension\ServiceContainer\MinkExtension as BehatMinkExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class MinkExtension
 *
 * @package Modelizer\Enfield\Extensions
 */
class MinkExtension extends BehatMinkExtension
{
    public function configure(ArrayNodeDefinition $builder)
    {
        parent::configure($builder);

        $builder
            ->children()
            ->scalarNode('auth_login')
            ->defaultNull();
    }
}
