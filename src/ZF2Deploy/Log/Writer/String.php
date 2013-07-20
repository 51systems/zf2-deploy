<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 19/07/13
 * Time: 10:15 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Log\Writer;


use Traversable;
use Zend\Log\Exception;
use Zend\Log\Filter;
use Zend\Log\Formatter;
use Zend\Log\Writer\AbstractWriter;
use Zend\Log\Writer\Logger;
use Zend\Log\Formatter\Simple as SimpleFormatter;

class String extends AbstractWriter
{
    /**
     * Separator between log entries
     *
     * @var string
     */
    protected $logSeparator = PHP_EOL;

    /**
     * A string represenation of the log
     * @var string
     */
    protected $logString = '';

    public function __construct($options = null)
    {
        parent::__construct($options);

        if ($this->formatter === null) {
            $this->formatter = new SimpleFormatter();
        }
    }

    /**
     * @inheritdoc
     */
    protected function doWrite(array $event)
    {
        $line = $this->formatter->format($event) . $this->logSeparator;
        $this->logString .= $line;
    }
}