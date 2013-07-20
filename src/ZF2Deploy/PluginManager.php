<?php

namespace ZF2Deploy;


use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
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
        'doctrineMigrationView' => 'ZF2Deploy\Plugin\Db\DoctrineMigrationView',
    );

    /**
     * Default set of plugin aliases
     *
     * @var array
     */
    protected $aliases = array(
        'echoPlugin' => 'echo'
    );

    //endregion


    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
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

            if ($self->eventManager != null && $instance instanceof EventManagerAwareInterface) {
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
}