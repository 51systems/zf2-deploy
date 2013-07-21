<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 3:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\Event;


use ZF2Deploy\Plugin\DeploymentPluginInterface;

/**
 * Class OutputString
 * @package ZF2Deploy\Plugin\Event
 *
 * Emitted when the executor should output a string visible to the user.
 */
class OutputString extends Plugin
{

    const EVENT_OUTPUT_STRING = 'output.string';

    /**
     * The string to output
     * @var string
     */
    protected $string;

    /**
     * @param DeploymentPluginInterface $plugin
     * @param DeploymentPluginInterface $string
     * @param array $params
     */
    public function __construct(DeploymentPluginInterface $plugin, $string, $params = null)
    {
        parent::__construct(static::EVENT_OUTPUT_STRING, $plugin, $params);

        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }
}