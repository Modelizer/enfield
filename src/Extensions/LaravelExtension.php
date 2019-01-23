<?php

namespace Modelizer\Enfield\Extensions;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Laracasts\Behat\ServiceContainer\BehatExtension;
use SilverStripe\MinkFacebookWebDriver\FacebookFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Laravel specific extension for chrome driver
 * @package App\Extensions
 */
class LaravelExtension extends BehatExtension
{
    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'facebook_web_driver';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        if (null !== $minkExtension = $extensionManager->getExtension('mink')) {
            $minkExtension->registerDriverFactory(new FacebookFactory);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
            ->scalarNode('bootstrap_path')
            ->defaultValue('bootstrap/app.php')
            ->end()
            ->scalarNode('env_path')
            ->defaultValue('.env.testing');
    }
}
