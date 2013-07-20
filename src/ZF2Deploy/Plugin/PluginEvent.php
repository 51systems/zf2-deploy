<?php

namespace ZF2Deploy\Plugin;

use ArrayAccess;
use Zend\EventManager\Event;

class PluginEvent extends Event
{
    const EVENT_OUTPUT_STRING = 'output.string';

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

    /**
     * Creates a new Output string event.
     * @param DeploymentPluginInterface $plugin
     * @param $output
     * @return PluginEvent
     */
    public static function OutputStringFactory(DeploymentPluginInterface $plugin, $output)
    {
        return new self(static::EVENT_OUTPUT_STRING, $plugin, array('output' => $output));
    }
}