<?php

namespace ZF2Deploy;


use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;

use ZF2Deploy\Exception\InvalidPluginException;
use ZF2Deploy\Plugin\AbstractPlugin;

class PluginManager extends AbstractPluginManager implements EventManagerAwareInterface
{
//region ServiceManager Config

    /**
     * Default set of plugins
     *
     * @var array
     */
    protected $invokableClasses = array(
        'echo' => 'ZF2Deploy\Plugin\EchoPlugin',

        'doctrineschemaupdate' => 'ZF2Deploy\Plugin\Db\Doctrine\SchemaUpdate',
        'doctrinegenerateproxies' => 'ZF2Deploy\Plugin\Db\Doctrine\GenerateProxies',
        'doctrineclearcache' => 'ZF2Deploy\Plugin\Db\Doctrine\ClearCache',

        'asseticsetup' => 'ZF2Deploy\Plugin\File\Assetic\Setup',
        'asseticbuild' => 'ZF2Deploy\Plugin\File\Assetic\Build',

        'extracttar' => 'ZF2Deploy\Plugin\File\ExtractTar',

        'symboliclink' => 'ZF2Deploy\Plugin\File\SymbolicLink',
        'delete' => 'ZF2Deploy\Plugin\File\Delete',

        'persistconfigtokens' => 'ZF2Deploy\Plugin\PersistConfigTokens'
    );

    /**
     * Default set of plugin aliases
     *
     * @var array
     */
    protected $aliases = array(
        'configtokens' => 'persistconfigtokens'
    );

    //endregion


    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The event manager that the plugin manager uses.
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);

        $self = $this;
        $this->addInitializer(function ($instance) use ($self) {
            if ($instance instanceof AbstractPlugin) {
                $instance->setLogger($self->getLogger());
            }

            if ($self->hasEventManager() && $instance instanceof EventManagerAwareInterface) {
                $instance->setEventManager($self->getEventManager());
            }
        });
    }

    /**
     * @param \Zend\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return \Zend\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }


    /**
     * @inheritdoc
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Plugin\DeploymentPluginInterface) {
            // we're okay
            return;
        }

        throw new InvalidPluginException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Plugin\DeploymentPluginInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }

    /**
     * @param EventManagerInterface $events
     * @return $this
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->eventManager = $events;
        return $this;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Checks if an event manager has been registered with the plugin manager.
     * @return bool
     */
    public function hasEventManager()
    {
        return $this->eventManager !== null;
    }
}