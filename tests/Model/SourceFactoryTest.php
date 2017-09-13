<?php

/**
 * Created by PhpStorm.
 * User: LordMondando
 * Date: 10/07/2016
 * Time: 15:53
 */
use Model\SourceFactory as SourceFactory;

class SourceFactoryTest extends PHPUnit_Framework_TestCase
{
    private $sourceFactory;

    public function setUp()
    {
        $this->sourceFactory = new SourceFactory();
    }

    public function testMyClass()
    {
        $params = array (
            'type' => 'MySql',
            'db_user' => 'test',
            'db_pass' => 'test',
            'db_host' => 'test',
            'db_port' => 'test',
            'db_name' => 'test'
        );

        $output = $this->sourceFactory->makeSource($params);

        $this->assertSame(get_class($output), 'Model\Sources\MySqlSource');
    }

    public function testExampleOtherClass()
    {
        $params = array (
            'type' => 'ExampleOther',
            'test' => 'test'
        );

        $output = $this->sourceFactory->makeSource($params);

        $this->assertSame(get_class($output), 'Model\Sources\ExampleOtherSource');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unable to connect to internal data source
     */
    public function testNonExistantClassException()
    {
        $params = array (
            'type' => 'NonExistent'
        );

        $this->sourceFactory->makeSource($params);
    }
}
