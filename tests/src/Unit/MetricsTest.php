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
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $symfonyContainerBuilder);
    }

    /**
     * Covers \EdisonLabs\Metrics\Collector
     */
    public function testCollector()
    {
        $date = time();
        $collector = new Collector($date);
        $metric = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
            ->setConstructorArgs(array(time()))
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
            ->setConstructorArgs(array(time()))
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
     * Covers \EdisonLabs\Metrics\DatastoreHandler
     */
    public function testDatastoreHandler()
    {
        $dataStore = $this->getMockBuilder('EdisonLabs\Metrics\Datastore\MetricDatastoreInterface')
            ->setMethods(['getName', 'getDescription', 'setConfig', 'getConfig', 'setDate', 'getDate', 'save', '__construct'])
            ->getMock();
        $dataStore->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $datastoreHandler = $this->getMockBuilder('EdisonLabs\Metrics\DatastoreHandler')
            ->setConstructorArgs(array(time()))
            ->getMockForAbstractClass();
        $datastoreHandler->setDatastore($dataStore);

        $datastores = $datastoreHandler->getDatastores();

        $this->assertNotNull($datastores);
        $this->assertEquals($dataStore, $datastoreHandler->getDatastoreByName('test'));
    }

    /**
     * Covers \EdisonLabs\Metrics\Datastore\AbstractMetricDatastore
     */
    public function testAbstractMetricDatastore()
    {
        $abstractMetricDatastoreMock = $this->getMockBuilder('EdisonLabs\Metrics\Datastore\AbstractMetricDatastore')
            ->setMethods(['getDescription', 'getName', 'save'])
            ->setConstructorArgs(array(time()))
            ->getMockForAbstractClass();
        $abstractMetricDatastoreMock->expects($this->once())
            ->method('getDescription')
            ->willReturn('Test metric datastore description');
        $abstractMetricDatastoreMock->expects($this->once())
            ->method('getName')
            ->willReturn('Test metric datastore');

        $configuration = ['test' => '123'];
        $abstractMetricDatastoreMock->setConfig($configuration);
        $config = $abstractMetricDatastoreMock->getConfig();
        $this->assertArrayHasKey('test', $config);
        $this->assertEquals($config['test'], $configuration['test']);
        $this->assertEquals('Test metric datastore', $abstractMetricDatastoreMock->getName());
        $this->assertEquals('Test metric datastore description', $abstractMetricDatastoreMock->getDescription());

        $metric = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
            ->setMethods(['getMetric'])
            ->setConstructorArgs(array(time()))
            ->getMockForAbstractClass();
        $metric->method('getMetric')
            ->willReturn(10);
        $metrics = [$metric];
        $abstractMetricDatastoreMock->setMetrics($metrics);
        $datastoreMetrics = $abstractMetricDatastoreMock->getMetrics();
        $this->assertNotEmpty($datastoreMetrics);
        $this->assertCount(1, $datastoreMetrics);
        $this->assertEquals(10, $datastoreMetrics[0]->getMetric());
    }
}
