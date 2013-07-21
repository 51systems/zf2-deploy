<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 7:03 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\File\Assetic;


use Clinner\Command\Command;
use Zend\Config\Config;

/**
 * Class Build
 * @package ZF2Deploy\Plugin\File\Assetic
 *
 * Builds the assets and places them in the asset folder
 */
class Build extends AbstractAssetic
{

    /**
     * @inheritdoc
     */
    protected function configureCommand(Command $cmd, $config)
    {
        $arguments = $cmd->getArguments()->getAll();
        array_push($arguments, 'build');
        $cmd->setArguments($arguments);

        return $cmd;
    }
}