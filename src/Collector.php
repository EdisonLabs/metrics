<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\MetricInterface;

/**
 * Class Collector
 */
class Collector
{

    const METRICS_NAMESPACE = 'EdisonLabs\Metrics';

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
    public function setMetric(MetricInterface $metric)
    {
        $this->metrics[] = $metric;
    }

    /**
     * Returns the available metrics.
     *
     * @return array
     *   An array containing the metrics objects.
     */
    public function getMetrics()
    {
        return $this->metrics;
    }

    /**
     * Set metrics to be collected.
     *
     * @throws \Exception
     */
    protected function setMetrics()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder = $containerBuilder->getContainerBuilder();

        $services = $containerBuilder->getServiceIds();
        foreach ($services as $serviceName) {
            if (strpos($serviceName, self::METRICS_NAMESPACE) === false) {
                continue;
            }

            $metric = $containerBuilder->get($serviceName);

            // Sanity check by class type.
            if (!$metric instanceof MetricInterface) {
                continue;
            }

            $this->setMetric($metric);
        }
    }
}
