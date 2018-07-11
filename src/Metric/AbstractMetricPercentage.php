<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricPercentage.
 */
abstract class AbstractMetricPercentage extends AbstractMetricBase
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
     * @param AbstractMetricCount $count
     *   The AbstractMetricCount object for the total value.
     * @param AbstractMetricCount $total
     *   The AbstractMetricCount object for the count value.
     */
    public function __construct(AbstractMetricCount $count, AbstractMetricCount $total)
    {
        $this->count = $count->getMetric();
        $this->total = $total->getMetric();
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
