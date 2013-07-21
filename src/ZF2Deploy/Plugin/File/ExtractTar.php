<?php

namespace ZF2Deploy\Plugin\File;


use Archive_Tar;
use Zend\Config\Config;
use ZF2Deploy\Plugin\AbstractPlugin;
use ZF2Deploy\Plugin\Event\ReplaceToken;

/**
 * Class ExtractToRelease
 * @package ZF2Deploy\Plugin\File
 *
 * Extracts a tar file to the specified directory.
 *
 * Accepted Configuration Values:
 *  archive     [Required]  - Full path to the archive to extract
 *  target-dir  [Required]  - Directory to extract the archive to
 *  use-version [Optional]  - If true, appends the version to the extraction path as a subdir
 *  version     [Optional]  - If set, uses this version rather than extracting the version from the archive name.
 *
 * Configuration Replacement Tokens:
 *  @TAR_TARGET_DIR  Path to directory that the archive has been extracted to
 */
class ExtractTar extends AbstractPlugin
{

    /**
     * Replacement token for target directory.
     * Will resolve to the  path to directory that the archive has been extracted to
     */
    const TOKEN_TARGET_DIR = '@TAR_TARGET_DIR';

    /**
     * Inits the PEAR Archive component
     * @throws \Exception
     */
    public function init()
    {
        include_once 'Archive/Tar.php';
        if (!class_exists('Archive_Tar')) {
            throw new \Exception("You must have installed the PEAR Archive_Tar class.");
        }
    }

    /**
     * @inheritdoc
     */
    public function run($config)
    {
        $this->init();

        if (empty($config['archive'])) {
            throw new \Exception("'archive' must be set");
        }

        if (empty($config['target-dir'])) {
            throw new \Exception("'target-dir' must be set");
        }

        $destDir = $this->calculateDestinationDir($config);

        $tarFile = $this->initTar($config['archive']);

        $this->info(sprintf("Extracting '%s' to '%s'", $config['archive'], $destDir));

        $tarFile->extract($destDir);

        $this->getEventManager()->trigger(new ReplaceToken($this, static::TOKEN_TARGET_DIR, $destDir));
    }

    /**
     * Calculates the destination directory where files will be extracted.
     * @param array|Config $config
     * @return string
     */
    private function calculateDestinationDir($config)
    {
        if (!isset($config['use-version']) || !$config['use-version']) {
            return $config['target-dir'];
        }

        //we are going to try to extract the tar to an automatically created version directory
        //beneath the target-dir directory.

        if (!empty($config['version'])) {
            $version = $config['version'];
        } else {
            if (preg_match('/([\d]+(?:\.[\d])*)/i', $config['archive'], $regs)) {
                $version = $regs[0];
            } else {
                throw new \Exception('Unable to extract version from archive name: ' . $config['archive']);
            }
        }

        return $config['target-dir'] . DIRECTORY_SEPARATOR . $version;
    }

    /**
     * Init a Archive_Tar class with correct compression for the given file.
     *
     * @param string $path Path to the tar file
     * @return Archive_Tar the tar class instance
     */
    private function initTar($path)
    {
        $compression = null;
        $tarfileName = basename($path);
        $mode = strtolower(substr($tarfileName, strrpos($tarfileName, '.')));

        $compressions = array(
            'gz' => array('.gz', '.tgz',),
            'bz2' => array('.bz2',),
        );
        foreach ($compressions as $algo => $ext) {
            if (array_search($mode, $ext) !== false) {
                $compression = $algo;
                break;
            }
        }

        return new Archive_Tar(realpath($path), $compression);
    }
}