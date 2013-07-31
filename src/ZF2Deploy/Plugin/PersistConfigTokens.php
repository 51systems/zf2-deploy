<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 30/07/13
 * Time: 5:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin;


use Zend\Config\Config;
use Zend\Session\AbstractContainer;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container as SessionContainer;
use Zend\Session\SessionManager;
use Zend\Stdlib\ArrayObject;
use ZF2Deploy\Plugin\Event\ReplaceToken as ReplaceTokenEvent;

/**
 * Class PersistConfigTokens
 * @package ZF2Deploy\Plugin
 *
 * Plugin that persists replacement tokens and can be used to restore them at a later time (Between runs, for example).
 *
 * Accepted Configuration Values:
 *  save    [Optional]  - If true, will save the config replacement tokens to the PHP session
 *  restore [Optional]  - If true will immediately restore the replacement tokens from the session.
 *  clear   [Optional]   - Clears the PHP session of the replacement tokens
 */
class PersistConfigTokens extends AbstractPlugin
{
    /**
     * @var SessionContainer
     */
    private $sessionContainer;

    /**
     * Runs the plugin
     * @param array|Config $config
     * @return void
     */
    function run($config)
    {
        if (isset($config['save']) && $config['save']) {
            $this->info('Config Token saving enabled');
            $this->eventManager->attach(ReplaceTokenEvent::EVENT_REPLACE_CONFIG_TOKEN, array(&$this, 'onReplaceToken'));
        }

        if (isset($config['restore']) && $config['restore']) {
            $this->info('Restoring && replacing config tokens');
            $session = $this->getSession();

            foreach ($session as $token => $value) {
                $this->getEventManager()->trigger(new ReplaceTokenEvent($this, $token, $value));
            }
        }

        if (isset($config['clear']) && $config['clear']) {
            $this->info('Clearing saved config tokens');
            $session = $this->getSession();
            $session->getManager()->getStorage()->clear($session->getName());
        }
    }

    /**
     * Called when a ReplaceTokenEvent is emitted
     *
     * @param ReplaceTokenEvent $event
     */
    public function onReplaceToken(ReplaceTokenEvent $event)
    {
        $this->info(sprintf('Saving token: %s Value:(%s)', $event->getToken(), $event->getValue()));
        $session = $this->getSession();
        $session[$event->getToken()] = $event->getValue();
    }


    /**
     * @return AbstractContainer
     */
    protected function getSession()
    {
        if (!isset($this->sessionContainer)) {
            $this->sessionContainer = new SessionContainer('PersistConfigTokens');
        }

        return $this->sessionContainer;
    }
}