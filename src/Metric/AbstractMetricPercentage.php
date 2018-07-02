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
     * @param MetricCountInterface $total
     *   The MetricCountInterface object for the total value.
     * @param MetricCountInterface $count
     *   The MetricCountInterface object for the count value.
     */
    public function __construct(MetricCountInterface $total, MetricCountInterface $count)
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
