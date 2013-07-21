<?php

namespace ZF2Deploy\Plugin\Db\Doctrine;


use Clinner\Command\Command;

/**
 * Class GenerateProxies
 * @package ZF2Deploy\Plugin\Db\Doctrine
 *
 * Generates Doctrine Proxy Objects
 */
class GenerateProxies extends AbstractDoctrine
{

    /**
     * @inheritdoc
     */
    protected function configureCommand(Command $cmd, $config)
    {
        $cmd->setArguments(array('orm:generate-proxies'));
    }
}