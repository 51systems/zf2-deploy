<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 11:42 AM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin\File;


use Zend\Config\Config;
use ZF2Deploy\Plugin\AbstractPlugin;

/**
 * Class SymbolicLink
 * @package ZF2Deploy\Plugin\File
 *
 * Create a symbolic link.
 *
 * Accepted Configuration Values:
 *  link     [Required]  - The path to the newly created link (AKA the alias that will resolve to the target)
 *  target   [Required]  - The target of the symbolic link (What it points at)
 */
class SymbolicLink extends AbstractPlugin
{
    /**
     * @inheritdoc
     */
    function run($config)
    {
        if (empty($config['link']))
            throw new \Exception("'link' must be specified");

        if (empty($config['target']))
            throw new \Exception("'target' must be specified");


        if (isset($config['hard']) && $config['hard']) {
            //Create a hard-link, rather than a symbolic link

            $this->info(sprintf("Creating hard link '%s' --> '%s'", $config['target'], $config['link']));

            if(!link($config['target'], $config['link']))
                $this->err('Error creating hard link');

            return;
        }

        $this->info(sprintf("Creating symbolic link '%s' --> '%s'", $config['target'], $config['link']));

        if(!symlink($config['target'], $config['link']))
            $this->err('Error creating symbolic link');
    }
}