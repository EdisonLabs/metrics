<?php

namespace EdisonLabs\Metrics\Unit;

use EdisonLabs\Metrics\Collector;
use EdisonLabs\Metrics\ContainerBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Tests generation of metrics.
 *
 * @group metrics
 */
class MetricsTest extends TestCase
{

    /**
     * Covers \EdisonLabs\Metrics\ContainerBuilder
     */
    public function testContainerBuilder()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder = $containerBuilder->getContainerBuilder();
        $this->assertInstanceOf('SymfonyContainerBuilder', $containerBuilder);
    }

    /**
     * Covers \EdisonLabs\Metrics\Collector
     */
    public function testCollector()
    {
        $collector = new Collector();
        $metrics = $collector->getMetrics();
        $this->assertNotNull($metrics);
    }
}
