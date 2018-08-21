<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\AbstractMetricBase;

/**
 * Class Collector
 */
class Collector
{

    const METRICS_NAMESPACE = 'EdisonLabs\Metric';

    /**
     * @var string
     */
    protected $date;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $groups;

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * Collector constructor.
     *
     * @param string $date   A date timestamp to collect for.
     * @param array  $config The custom config array.
     * @param array  $groups A list containing the groups to filter for.
     *
     * @throws \Exception
     */
    public function __construct($date, array $config = array(), array $groups = array())
    {
        $this->groups = $groups;
        $this->config = $config;
        $this->date = $date;
        $this->setMetrics();
    }

    /**
     * Register a metric to be collected.
     *
     * @param AbstractMetricBase $metric The Metric object.
     */
    public function setMetric(AbstractMetricBase $metric)
    {
        $this->metrics[] = $metric;
    }

    /**
     * Returns the available metrics.
     *
     * @return array An array containing the metrics objects.
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
        $containerBuilder = new ContainerBuilder($this->date, $this->config);
        $containerBuilder = $containerBuilder->getContainerBuilder();

        $services = $containerBuilder->getServiceIds();

        foreach ($services as $serviceName) {
            if (strpos($serviceName, self::METRICS_NAMESPACE) === false) {
                continue;
            }

            /** @var \EdisonLabs\Metrics\Metric\MetricInterface $metric */
            $metric = $containerBuilder->get($serviceName);

            // Sanity check by class type.
            if (!$metric instanceof AbstractMetricBase) {
                continue;
            }

            $metricGroups = $metric->getGroups();
            if (!empty($this->groups) && count(array_intersect($this->groups, $metricGroups)) == 0) {
                continue;
            }

            $this->setMetric($metric);
        }
    }
}
