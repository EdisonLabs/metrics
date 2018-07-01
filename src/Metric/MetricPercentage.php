<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class MetricPercentage.
 *
 * @package EdisonLabs\Metrics\Metric
 */
abstract class MetricPercentage implements MetricInterface
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
     * MetricPercentage constructor.
     *
     * @param MetricCount $total
     *   The MetricCount object for the total value.
     * @param MetricCount $count
     *   The MetricCount object for the count value.
     */
    public function __construct(MetricCount $total, MetricCount $count)
    {
        $this->total = $total->getMetric();
        $this->count = $count->getMetric();
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
    public function getMetric()
    {
        // Round half up. Making 1.5 into 2 and 1.4 into 1.
        return round((100 * $this->count) / $this->total);
    }

}
