<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 4:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\Event;


use ZF2Deploy\Plugin\DeploymentPluginInterface;

/**
 * Class ReplaceConfigToken
 * @package ZF2Deploy\Plugin\Event
 *
 * Emitted when a token in the global configuration must be updated.
 */
class ReplaceToken extends Plugin
{
    const EVENT_REPLACE_CONFIG_TOKEN = 'replace.config.token';

    /**
     * Token to replace
     * @var string
     */
    protected $token;

    /**
     * Value to replace the {@link #token} with
     * @var mixed
     */
    protected $value;

    /**
     * @param DeploymentPluginInterface $plugin
     * @param DeploymentPluginInterface $token
     * @param array|null $value
     * @param null $params
     */
    public function __construct(DeploymentPluginInterface $plugin, $token, $value, $params = null)
    {
        parent::__construct(self::EVENT_REPLACE_CONFIG_TOKEN, $plugin, $params);

        $this->token = $token;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}