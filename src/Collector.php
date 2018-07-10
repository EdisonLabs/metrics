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
     * @var array
     */
    protected $groups;

    /**
     * Collector constructor.
     *
     * @param array $groups
     *   A list containing the groups to filter for.
     *
     * @throws \Exception
     */
    public function __construct(array $groups = array())
    {
        $this->groups = $groups;
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

            $metric_groups = $metric->getGroups();
            if (!empty($this->groups) && count(array_intersect($this->groups, $metric_groups)) == 0) {
                continue;
            }

            $this->setMetric($metric);
        }
    }
}
