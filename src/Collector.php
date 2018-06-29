<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\MetricInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Class Collector
 * @package EdisonLabs\Metrics
 */
class Collector
{

    const METRICS_NAMESPACE = 'EdisonLabs\Metrics';
    const SERVICES_PHP_FILE = '../config/services.php';

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * Collector constructor.
     */
    public function __construct()
    {
        $this->setMetrics();
    }

    /**
     * Register a metric to be collected.
     *
     * @param MetricInterface $metric
     *   The Metric object.
     */
    public function setMetric(MetricInterface $metric) {
        $this->metrics[] = $metric;
    }

    /**
     * Set metrics to be collected.
     *
     * @throws \Exception
     */
    protected function setMetrics() {
        $containerBuilder = new ContainerBuilder();
        $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
        $loader->load(self::SERVICES_PHP_FILE);
        $containerBuilder->compile();

        $services = $containerBuilder->getServiceIds();
        foreach ($services as $service_name) {
            if (strpos($service_name, self::METRICS_NAMESPACE) === false) {
                continue;
            }

            $metric = $containerBuilder->get($service_name);
            $this->setMetric($metric);
        }
    }

    /**
     * Returns the metrics values.
     *
     * @return array
     *   An array containing the metrics values.
     */
    public function getMetrics() {
        $metrics_values = array();

        foreach ($this->metrics as $metric) {
            $metrics_values[get_class($metric)] = array(
                'name' => $metric->getName(),
                'description' => $metric->getDescription(),
                'value' => $metric->getMetric(),
            );
        }

        return $metrics_values;
    }

}
