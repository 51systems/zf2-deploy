<?php

namespace ZF2Deploy\Plugin\Event;


use Zend\EventManager\Event as BaseEvent;
use ZF2Deploy\Plugin\DeploymentPluginInterface;

class Plugin extends BaseEvent
{
    const EVENT_FAILURE = 'plugin.failure';

    /**
     * @var DeploymentPluginInterface
     */
    protected $plugin;

    /**
     * @param string $name
     * @param DeploymentPluginInterface $plugin
     * @param array $params
     */
    public function __construct($name, DeploymentPluginInterface $plugin, $params = null)
    {
        parent::__construct($name, $this, $params);

        $this->setPlugin($plugin);
    }

    /**
     * @param \ZF2Deploy\Plugin\DeploymentPluginInterface $plugin
     * @return $this
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }

    /**
     * @return \ZF2Deploy\Plugin\DeploymentPluginInterface
     */
    public function getPlugin()
    {
        return $this->plugin;
    }
}