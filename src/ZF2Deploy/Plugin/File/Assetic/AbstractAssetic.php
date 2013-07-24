<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 6:58 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\File\Assetic;

use Zend\Config\Config;
use ZF2Deploy\PhpCliHelper;
use ZF2Deploy\Plugin\AbstractPlugin;
use Clinner\Command\Command;
use ZF2Deploy\Plugin\Event\OutputString as OutputStringEvent;

/**
 * Class AbstractAssetic
 * @package ZF2Deploy\Plugin\File\Assetic
 *
 * Base class for plugins using the Assetic console.
 */
abstract class AbstractAssetic extends AbstractPlugin
{
    /**
     * @inheritdoc
     */
    public function run($config)
    {
        if (!isset($config['assetic-console-path'])) {
            throw new \Exception('assetic-console-path must be set');
        }

        $cmd = Command::create(PhpCliHelper::getPhpBin(), array($config['assetic-console-path'], 'assetic'));
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