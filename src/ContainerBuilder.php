<?php

namespace EdisonLabs\Metrics;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Class ContainerBuilder
 */
class ContainerBuilder
{

    const SERVICES_PHP_FILE = '../config/services.php';

    protected $containerBuilder;

    /**
     * ContainerBuilder constructor.
     *
     * @param string $date
     *   The date (timestamp) of the metrics.
     * @param array $config
     *   The custom config array.
     *
     * @throws \Exception
     */
    public function __construct($date, $config)
    {
        $containerBuilder = new SymfonyContainerBuilder();
        $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
        $loader->load(self::SERVICES_PHP_FILE);

        $containerBuilder->setParameter('metrics.date', $date);
        $containerBuilder->setParameter('metrics.config', $config);
        $containerBuilder->compile();

        $this->containerBuilder = $containerBuilder;
    }

    /**
     * Returns the container builder instance.
     *
     * @return SymfonyContainerBuilder
     *   Container builder instance.
     */
    public function getContainerBuilder()
    {
        return $this->containerBuilder;
    }
}
