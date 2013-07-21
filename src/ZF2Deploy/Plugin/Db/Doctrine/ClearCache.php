<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 6:30 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\Db\Doctrine;

use Clinner\Command\Command;

/**
 * Class ClearCache
 * @package ZF2Deploy\Plugin\Db\Doctrine
 *
 * Plugin to clear various Doctrine Caches
 */
class ClearCache extends AbstractDoctrine
{
    /**
     * Metadata Cache (stores entity metadata)
     */
    const CACHE_METADATA = 'metadata';

    /**
     * Stores parsed DQL queries
     */
    const CACHE_RESULT = 'query';

    /**
     * Stores query results
     */
    const CACHE_QUERY = 'result';

    /**
     * List of valid caches
     * @var array
     */
    protected $validCaches = array(
        self::CACHE_METADATA,
        self::CACHE_RESULT,
        self::CACHE_QUERY
    );

    /**
     * @inheritdoc
     */
    protected function configureCommand(Command $cmd, $config)
    {
        if (empty($config['cache'])) {
            throw new \Exception("'cache' must be specified");
        }

        if (!in_array($config['cache'], $this->validCaches)) {
            throw new \Exception(sprintf("cache must be one of %s, got:'%s'", join(',', $this->validCaches), $config['cache']));
        }

        $cmd->setArguments(array('orm:clear-cache:' . $config['cache']));

        return $cmd;
    }
}