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

    /**
     * Covers \EdisonLabs\Metrics\Metric\AbstractMetricPercentage
     */
    public function testAbstractMetricPercentage()
    {
        $count = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
            ->setMethods(['getMetric'])
            ->getMockForAbstractClass();
        $count->expects($this->once())
            ->method('getMetric')
            ->willReturn(3);

        $total = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
            ->setMethods(['getMetric'])
            ->getMockForAbstractClass();
        $total->expects($this->once())
            ->method('getMetric')
            ->willReturn(10);

        $metricPercentage = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricPercentage')
            ->setConstructorArgs([$count, $total]);
        $metricPercentage = $metricPercentage->getMockForAbstractClass();

        $metric = $metricPercentage->getMetric();
        $this->assertNotNull($metric);
        $this->assertEquals(30, $metric);
    }

    /**
     * Covers \EdisonLabs\Metrics\DatastoreHandler
     */
    public function testDatastoreHandler()
    {
        $dataStore = $this->getMockBuilder('EdisonLabs\Metrics\Datastore\MetricDatastoreInterface')
            ->setMethods(['getName', 'getDescription', 'setConfig', 'getConfig', 'save'])
            ->getMock();
        $dataStore->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $datastoreHandler = $this->getMockBuilder('EdisonLabs\Metrics\DatastoreHandler')
            ->setConstructorArgs([[], [$dataStore]])
            ->getMock();

        $this->assertNotNull($datastoreHandler->getDatastores());
        $this->assertEquals($dataStore, $datastoreHandler->getDatastoreByName('test'));
    }
}
