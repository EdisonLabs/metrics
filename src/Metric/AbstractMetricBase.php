<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricBase.
 */
abstract class AbstractMetricBase implements MetricInterface
{

    /**
     * The metrics custom parameters.
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * Sets the metrics custom parameters.
     *
     * @param array $parameters
     *   An array containing the metrics parameters.
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns the metrics custom parameters.
     *
     * @return array
     *   An array containing the metrics parameters.
     */
    public function getParameters()
    {
        return $this->parameters;
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
