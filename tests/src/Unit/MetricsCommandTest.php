<?php

namespace EdisonLabs\Metrics\Unit;

use PHPUnit\Framework\TestCase;
use EdisonLabs\Metrics\Command\MetricsCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests MetricsCommand.
 *
 * @group metrics
 */
class MetricsCommandTest extends TestCase
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
     * Tests the MetricsCommand.
     */
    public function testMetricsCommand()
    {
        $command = new MetricsCommand();

        // Set metric.
        $metric = $this->getMockBuilder('EdisonLabs\Metrics\Metric\AbstractMetricBase')
          ->setMethods(array('getMetric', 'getName', 'getDescription', 'getGroups'))
          ->setConstructorArgs(array(time()))
          ->getMockForAbstractClass();
        $metric->method('getMetric')
          ->willReturn(10);
        $metric->method('getName')
          ->willReturn('Test metric name');
        $metric->method('getDescription')
          ->willReturn('Test metric description');
        $metric->method('getGroups')
          ->willReturn(array('group_test'));
        $command->setMetrics(array($metric));

        // Test command options.
        $this->assertEquals('edisonlabs:metrics', $command->getName());
        $this->assertEquals('Edison Labs metrics collector', $command->getDescription());
        $this->assertTrue($command->getDefinition()->hasOption('format'));
        $this->assertTrue($command->getDefinition()->hasOption('list-datastores'));
        $this->assertTrue($command->getDefinition()->hasOption('save'));
        $this->assertTrue($command->getDefinition()->hasOption('no-messages'));
        $this->assertTrue($command->getDefinition()->hasOption('groups'));
        $this->assertTrue($command->getDefinition()->hasOption('config'));

        $tester = new CommandTester($command);

        // Test 'list-datastores'.
        $tester->execute(array(
            '--list-datastores' => null,
        ));
        $output = $tester->getDisplay();
        $this->assertContains('| Name | Description |', $output);

        // Test metric output table.
        $tester->execute(array());
        $output = $tester->getDisplay();
        $this->assertContains('| Test metric name | Test metric description | group_test | 10    |', $output);

        // Test other options.
        $config = '{"key": "value"}';
        $tester->execute(array(
            '--config' => $config,
            '--groups' => 'group_test',
            '--save' => 'SQLite',
            '--format' => 'json',
        ));
        $this->assertEquals(0, $tester->getStatusCode());
        $output = $tester->getDisplay();
        $this->assertContains('[WARNING] Unable to find datastore SQLite', $output);
        $this->assertContains('{"name":"Test metric name","description":"Test metric description","groups":["group_test"],"value":10}}', $output);
    }
}
