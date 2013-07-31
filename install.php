<?php

chdir(__DIR__);
ini_set('display_errors', 1);
error_reporting(E_ALL | E_WARNING);
// Setup autoloading
require 'vendor/autoload.php';

if (!file_exists('deploy-config.php')) {
    throw new Exception('deploy-config.php must exist at the same level as install.php');
}

session_start();

$config = include_once 'deploy-config.php';

if (!($config instanceof \Zend\Config\Config))
    $config = new \Zend\Config\Config($config, true);

if (!isset($config['profiles'])) {

    //Create a default profile for use
    $config = new \Zend\Config\Config(array(
        'profiles' => array (
            'Default' => $config
    )), true);
}

//Check to see if we are executing a profile
if (isset($_POST['profile']) && isset($config['profiles'][$_POST['profile']])) {
    echo "<h1>{$_POST['profile']} Profile Executed</h1>";
    $httpExecutor = new \ZF2Deploy\Executor\Html();
    $httpExecutor->run($config['profiles'][$_POST['profile']]);

    echo "<h2>Plugin Output</h2>";
    echo nl2br($httpExecutor->getPluginOutput());

    echo "<h2>Log</h2>";
    echo nl2br($httpExecutor->getLogOutput());
    die();
}

?>

<html>
<head>
    <title>ZF2 Deploy</title>
</head>
<body>
    <h1>Execute Profile</h1>
    <form method="post">

        <?php foreach ($config['profiles'] as $key => $value): ?>
            <input type="submit" name="profile" value="<?php echo $key?>"/><br/>
        <?php endforeach; ?>

    </form>

<div style="text-align: right"><?php echo ZF2Deploy\Version::VERSION?></div>
</body>
</html>