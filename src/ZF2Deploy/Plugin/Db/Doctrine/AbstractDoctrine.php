<?php

namespace ZF2Deploy\Plugin\Db\Doctrine;

use Zend\Config\Config;
use ZF2Deploy\Plugin\AbstractPlugin;
use Clinner\Command\Command;
use ZF2Deploy\Plugin\Event\OutputString as OutputStringEvent;

/**
 * Class AbstractDoctrine
 * @package ZF2Deploy\Plugin\Db\Doctrine
 *
 * Base class for plugins using the Doctrine-Console
 */
abstract class AbstractDoctrine extends AbstractPlugin
{
    /**
     * @inheritdoc
     */
    public function run($config)
    {
        if (!isset($config['doctrine-console-path'])) {
            throw new \Exception('doctrine-console-path must be set');
        }



        $cmd = Command::create($config['doctrine-console-path']);
        $this->configureCommand($cmd, $config);

        $this->info(sprintf('Executing %s', $cmd->toCommandString()));

        $output = $cmd
            ->run()
            ->getOutput();

        $errOutput = $cmd->getErrorOutput();
        if (!empty($errOutput))
            $this->err('Error executing command: ' . $errOutput);


        if (!empty($output))
            $this->getEventManager()->trigger(new OutputStringEvent($this, "\n" . $output));
    }

    /**
     * Configures the specified command with arguments etc...
     *
     * @param Command $cmd
     * @param array|Config $config
     * @return Command
     */
    protected abstract function configureCommand(Command $cmd, $config);


}