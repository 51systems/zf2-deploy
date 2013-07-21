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


class SymbolicLink extends AbstractPlugin
{
    /**
     * @inheritdoc
     */
    function run($config)
    {
        if (empty($config['src']))
            throw new \Exception("'src' must be specified");

        if (empty($config['dest']))
            throw new \Exception("'dest' must be specified");


        if (isset($config['hard']) && $config['hard']) {
            //Create a hard-link, rather than a symbolic link

            $this->info(sprintf("Creating hard link '%s' --> '%s'", $config['src'], $config['dest']));

            if(!link($config['dest'], $config['src']))
                $this->err('Error creating hard link');

            return;
        }

        $this->info(sprintf("Creating symbolic link '%s' --> '%s'", $config['src'], $config['dest']));

        if(!symlink($config['dest'], $config['src']))
            $this->err('Error creating symbolic link');
    }
}