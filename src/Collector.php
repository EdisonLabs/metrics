<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\AbstractMetricBase;

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
     * @var array
     */
    protected $config;

    /**
     * Collector constructor.
     *
     * @param array $groups
     *   A list containing the groups to filter for.
     * @param array $config
     *   The custom config array.
     *
     * @throws \Exception
     */
    public function __construct(array $groups = array(), array $config = array())
    {
        $this->groups = $groups;
        $this->config = $config;
        $this->setMetrics();
    }

    /**
     * Register a metric to be collected.
     *
     * @param AbstractMetricBase $metric
     *   The Metric object.
     */
    public function setMetric(AbstractMetricBase $metric)
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
            if (!$metric instanceof AbstractMetricBase) {
                continue;
            }

            $metricGroups = $metric->getGroups();
            if (!empty($this->groups) && count(array_intersect($this->groups, $metricGroups)) == 0) {
                continue;
            }

            $metric->setConfig($this->config);
            $this->setMetric($metric);
        }
    }
}
