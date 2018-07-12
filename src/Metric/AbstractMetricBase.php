<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricBase.
 */
abstract class AbstractMetricBase implements MetricInterface
{

    /**
     * The metrics config.
     *
     * @var array
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        // No groups by default.
        return array();
    }
}
