<?php

namespace ZF2Deploy\Test\Executor;

use Zend\Config\Config;
use ZF2Deploy\Executor\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
    public function testSinglePlugin()
    {
        $executor = new Html();

        $config = new Config(array(
            'plugins' => array(
                'echo' => array(
                    'message' => 'Hello World'
                )
            )
        ));

        $executor->run($config);

        $this->assertContains('Hello World', $executor->getPluginOutput());
        $this->assertContains('Outputing non-empty message', $executor->getLogOutput());
    }

    public function testMultiplePlugin()
    {
        $executor = new Html();

        $config = new Config(array(
            'plugins' => array(
                'echo' => array(
                    array(
                        'message' => 'Foo'
                    ),
                    array(
                        'message' => 'Bar'
                    )
                ),
            )
        ));

        $executor->run($config);

        $this->assertContains('Foo', $executor->getPluginOutput());
        $this->assertContains('Bar', $executor->getPluginOutput());
        $this->assertContains('Outputing non-empty message', $executor->getLogOutput());
    }
}
