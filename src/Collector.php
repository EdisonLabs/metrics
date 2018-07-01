<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\MetricInterface;

/**
 * Class Collector
 * @package EdisonLabs\Metrics
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
     * Set metrics to be collected.
     *
     * @throws \Exception
     */
    protected function setMetrics()
    {
        $container_builder = new ContainerBuilder();
        $container_builder = $container_builder->getContainerBuilder();

        $services = $container_builder->getServiceIds();
        foreach ($services as $service_name) {
            if (strpos($service_name, self::METRICS_NAMESPACE) === false) {
                continue;
            }

            $metric = $container_builder->get($service_name);

            // Sanity check by class type.
            if (!$metric instanceof MetricInterface) {
                continue;
            }

            $this->setMetric($metric);
        }
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

}
