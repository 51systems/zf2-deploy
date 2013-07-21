<?php

namespace ZF2Deploy\Plugin\Db\Doctrine;


use Clinner\Command\Command;
use Zend\Config\Config;


/**
 * Class DoctrineMigrationView
 * @package ZF2Deploy\Plugin\Db
 *
 * Plugin to display the SQL that is required to upgrade the database.
 * Uses the doctrine command line utility to generate the SQL.
 */
class SchemaUpdate extends AbstractDoctrine
{

    /**
     * @inheritdoc
     */
    protected function configureCommand(Command $cmd, $config)
    {
        return $cmd->setArguments(array('orm:schema-tool:update', '--dump-sql'));
    }
}