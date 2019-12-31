<?php

/**
 * @see       https://github.com/laminas/laminas-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-config/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Config\Writer;

use Laminas\Config\Config;
use Laminas\Config\Reader\Yaml as YamlReader;
use Laminas\Config\Writer\Yaml as YamlWriter;

/**
 * @category   Laminas
 * @package    Laminas_Config
 * @subpackage UnitTests
 * @group      Laminas_Config
 */
class YamlTest extends AbstractWriterTestCase
{
    public function setUp()
    {
        if (!constant('TESTS_LAMINAS_CONFIG_YAML_ENABLED')) {
            $this->markTestSkipped('Yaml test for Laminas\Config skipped');
        }

        if (constant('TESTS_LAMINAS_CONFIG_YAML_LIB_INCLUDE')) {
            require_once constant('TESTS_LAMINAS_CONFIG_YAML_LIB_INCLUDE');
        }

        $yamlReader = explode('::', constant('TESTS_LAMINAS_CONFIG_READER_YAML_CALLBACK'));
        if (isset($yamlReader[1])) {
            $this->reader = new YamlReader(array($yamlReader[0], $yamlReader[1]));
        } else {
            $this->reader = new YamlReader(array($yamlReader[0]));
        }

        $yamlWriter = explode('::', constant('TESTS_LAMINAS_CONFIG_WRITER_YAML_CALLBACK'));
        if (isset($yamlWriter[1])) {
            $this->writer = new YamlWriter(array($yamlWriter[0], $yamlWriter[1]));
        } else {
            $this->writer = new YamlWriter(array($yamlWriter[0]));
        }
    }

    public function testNoSection()
    {
        $config = new Config(array('test' => 'foo', 'test2' => array('test3' => 'bar')));

        $this->writer->toFile($this->getTestAssetFileName(), $config);

        $config = $this->reader->fromFile($this->getTestAssetFileName());

        $this->assertEquals('foo', $config['test']);
        $this->assertEquals('bar', $config['test2']['test3']);
    }

    public function testWriteAndReadOriginalFile()
    {
        $config = $this->reader->fromFile(__DIR__ . '/_files/allsections.yaml');

        $this->writer->toFile($this->getTestAssetFileName(), $config);

        $config = $this->reader->fromFile($this->getTestAssetFileName());

        $this->assertEquals('multi', $config['all']['one']['two']['three']);

    }
}
