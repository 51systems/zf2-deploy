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
 *
 * Accepted Configuration Values:
 *  execute [Optional]  - If true, will execute the SQL needed to update the schema, otherwise just displays it.
 */
class SchemaUpdate extends AbstractDoctrine
{

    /**
     * @inheritdoc
     */
    protected function configureCommand(Command $cmd, $config)
    {
        $action = null;

        if (isset($config['execute']) && $config['execute']) {
            $action = '--force';
        }

        if ($action == null) {
            $action = '--dump-sql';
        }

        return $cmd->setArguments(array('orm:schema-tool:update', $action));
    }
}