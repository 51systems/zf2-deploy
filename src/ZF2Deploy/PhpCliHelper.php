<?php

namespace ZF2Deploy;
use Clinner\Command\Command;


/**
 * Class PhpCliHelper
 * @package ZF2Deploy
 *
 * Gets a reference to the appropriate PHP command.
 * Will preferentially use a php-cli binary, but will fall back to the default php command on the system.
 *
 * Uses the unix whereis command.
 */
class PhpCliHelper
{
    /**
     * @var string
     */
    private static $path;

    /**
     * Returns the PHP binary.
     *
     * @return string
     */
    public static function getPhpBin()
    {
        if (static::$path == null) {
            static::$path = static::locatePath();
        }

        return static::$path;
    }

    /**
     * Attempts to locate the PHP path.
     *
     * @return string
     */
    private static function locatePath()
    {
        try {
            $output = Command::create('whereis', array('php-cli'))
                ->run()
                ->getOutput();

            if (preg_match('/php-cli:[\s]*([^\s]+).*$/i', $output, $regs)) {
                return $regs[1];
            }

        } catch (\Exception $e) {
        }

        //Default to php and hope it's on the path.
        return 'php';
    }

}