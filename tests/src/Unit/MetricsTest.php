<?php

namespace EdisonLabs\Metrics\Unit;

use EdisonLabs\Metrics\Collector;
use EdisonLabs\Metrics\ContainerBuilder;
use EdisonLabs\Metrics\Metric\AbstractMetricBase;
use EdisonLabs\Metrics\Metric\MetricInterface;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\TestCase;

/**
 * Tests generation of metrics.
 *
 * @group metrics
 */
class MetricsTest extends TestCase
{

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!defined('EDISONLABS_COMPOSER_INSTALL')) {
            $root = __DIR__.'/../../';
            $sources = array(
                $root.'/../../autoload.php',
                $root.'/../vendor/autoload.php',
                $root.'/vendor/autoload.php',
            );

            foreach ($sources as $file) {
                if (file_exists($file)) {
                    define('EDISONLABS_COMPOSER_INSTALL', $file);
                    break;
                }
            }
        }
    }

    /**
     * Covers \EdisonLabs\Metrics\ContainerBuilder
     */
    public function testContainerBuilder()
    {
        $containerBuilder = new ContainerBuilder();
        $symfonyContainerBuilder = $containerBuilder->getContainerBuilder();
        static::assertThat($symfonyContainerBuilder, new IsInstanceOf('\Symfony\Component\DependencyInjection\ContainerBuilder'));
    }

    /**
     * Covers \EdisonLabs\Metrics\Collector
     */
    public function testCollector()
    {
        $collector = new Collector();
        $metric = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
            ->getMockForAbstractClass();
        $collector->setMetric($metric);
        $metrics = $collector->getMetrics();
        $this->assertNotEmpty($metrics);
        $this->assertTrue(is_array($metrics));
    }

    /**
     * Covers \EdisonLabs\Metrics\Metric\AbstractMetricBase
     *
     * @return null
     */
    public function testAbstractMetricBase()
    {
        $abstractMetricBaseMock = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
          ->setMethods(['getMetric', 'getDescription', 'getName'])
          ->getMockForAbstractClass();
        $abstractMetricBaseMock->expects($this->once())
          ->method('getMetric')
          ->willReturn(666);
        $abstractMetricBaseMock->expects($this->once())
          ->method('getDescription')
          ->willReturn('Test metric description');
        $abstractMetricBaseMock->expects($this->once())
          ->method('getName')
          ->willReturn('Test metric');

        $configuration = ['test' => '123'];
        $abstractMetricBaseMock->setConfig($configuration);
        $config = $abstractMetricBaseMock->getConfig();
        $this->assertArrayHasKey('test', $config);
        $this->assertEquals($config['test'], $configuration['test']);
        $this->assertEquals('Test metric', $abstractMetricBaseMock->getName());
        $this->assertEquals('Test metric description', $abstractMetricBaseMock->getDescription());
        $this->assertEquals(666, $abstractMetricBaseMock->getMetric());
        $groups = $abstractMetricBaseMock->getGroups();
        $this->assertTrue(is_array($groups));
        $this->assertEmpty($groups);
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
            ->setMethods(['getDatastores'])
            ->getMock();
        $datastoreHandler->expects($this->any())
            ->method('getDatastores')
            ->willReturn([$dataStore]);

        $this->assertNotNull($datastoreHandler->getDatastores());
        $this->assertEquals($dataStore, $datastoreHandler->getDatastoreByName('test'));
    }
}
