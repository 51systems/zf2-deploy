<?php

namespace ZF2Deploy\Test\Executor;

use Zend\Config\Config;
use ZF2Deploy\Executor\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
    public function testFoo()
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
    }
}
