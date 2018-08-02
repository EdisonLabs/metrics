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

        // Create a temporal sqlite db file.
        new \PDO("sqlite:/tmp/metricsdatastoresqlitetest.sqlite");
    }

    /**
     * Tests the MetricsCommand.
     */
    public function testMetricsCommand()
    {
        $command = new MetricsCommand();
        $this->assertEquals('edisonlabs:metrics', $command->getName());
        $this->assertEquals('Edison Labs metrics collector', $command->getDescription());
        $this->assertTrue($command->getDefinition()->hasOption('format'));
        $this->assertTrue($command->getDefinition()->hasOption('list-datastores'));
        $this->assertTrue($command->getDefinition()->hasOption('save'));
        $this->assertTrue($command->getDefinition()->hasOption('no-messages'));
        $this->assertTrue($command->getDefinition()->hasOption('groups'));
        $this->assertTrue($command->getDefinition()->hasOption('config'));

        $tester = new CommandTester($command);
        // @TODO Figure out how get the command to use a valid datastore.
        $config = [];
        $tester->execute([
            '--config' => $config,
            '--save' => 'SQLite',
            '--format' => 'json',
        ]);
        $this->assertEquals(0, $tester->getStatusCode());
        $result = $tester->getDisplay();
        // print_r($result);
        $this->assertContains('[WARNING] Unable to find datastore SQLite', $result);
    }
}
