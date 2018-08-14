<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricBase.
 */
abstract class AbstractMetricBase implements MetricInterface
{
    /**
     * The metric date (timestamp).
     *
     * @var string
     */
    protected $date;

    /**
     * The metric config.
     *
     * @var array
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function __construct($date, array $config = array())
    {
        $this->setDate($date);
        $this->setConfig($config);
    }

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
    public function setDate($date)
    {
        return $this->date = $date;
    }

    /**
     * {@inheritdoc}
     */
    public function getDate()
    {
        return $this->date;
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
