<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricPercentage.
 */
abstract class AbstractMetricPercentage implements MetricInterface
{

    /**
     * The total value to calculate the percentage.
     *
     * @var int
     */
    protected $total;

    /**
     * The count value to calculate the percentage.
     *
     * @var int
     */
    protected $count;

    /**
     * AbstractMetricPercentage constructor.
     *
     * @param MetricCountInterface $count
     *   The MetricCountInterface object for the total value.
     * @param MetricCountInterface $total
     *   The MetricCountInterface object for the count value.
     */
    public function __construct(MetricCountInterface $count, MetricCountInterface $total)
    {
        $this->count = $count->getMetric();
        $this->total = $total->getMetric();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * {@inheritdoc}
     */
    abstract public function getDescription();

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        // No groups by default.
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getMetric()
    {
        // Round half up. Making 1.5 into 2 and 1.4 into 1.
        return round((100 * $this->count) / $this->total);
    }
}
