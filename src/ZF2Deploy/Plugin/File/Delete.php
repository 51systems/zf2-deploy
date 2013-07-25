<?php

namespace ZF2Deploy\Plugin\File;


use Zend\Config\Config;
use ZF2Deploy\Plugin\AbstractPlugin;

/**
 * Class Delete
 * @package ZF2Deploy\Plugin\File
 *
 * Deletes a file or directory from the filesystem
 *
 * Accepted Configuration Values:
 *  path        [Required]  - Full path to the file / directory to remove
 *  recursive   [Optional]  - If path is a directory, recursively removes the directory tree
 */
class Delete extends AbstractPlugin
{

    /**
     * Runs the plugin
     * @param array|Config $config
     * @return void
     */
    function run($config)
    {
        $path = $config['path'];

        if  (!file_exists($path)) {
            $this->warn(sprintf('Path %s does not exist', $path));
            return;
        }

        if (is_dir($path) && !is_link($path)) {

            if (isset($config['recursive']) && $config['recursive']) {
                $this->info(sprintf('Removing directory recursively: %s', $path));
                $this->rmDirRecursive($path);
                return;
            }

            $this->info(sprintf('Removing directory: %s', $path));
            rmdir($path);
            return;
        }

        $this->info(sprintf('Removing file: %s', $path));
        unlink($path);
    }

    /**
     * Recursively removes all files under the specified directory, and then removes the directory.
     * @param string $dir
     *
     * @return bool true on success, false otherwise
     */
    private function rmDirRecursive($dir)
    {
        $it = new \RecursiveDirectoryIterator($dir);
        $it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($it as $file) {
            /** @var \SplFileInfo $file */
            if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
            if ($file->isDir()) rmdir($file->getPathname());
            else unlink($file->getPathname());
        }
        return rmdir($dir);
    }
}