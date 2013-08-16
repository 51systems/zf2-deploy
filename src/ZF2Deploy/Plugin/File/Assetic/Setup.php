<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 7:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\File\Assetic;


use Clinner\Command\Command;
use Zend\Config\Config;

/**
 * Class Setup
 * @package ZF2Deploy\Plugin\File\Assetic
 *
 * Create cache and assets directory with valid permissions
 */
class Setup extends AbstractAssetic
{
    /**
     * @inheritdoc
     */
    protected function configureCommand(Command $cmd, $config)
    {
        $arguments = $cmd->getArguments()->getAll();
        array_push($arguments, 'setup');
        $cmd->setArguments($arguments);
        return $cmd;
    }
}