<?php

if (PHP_SAPI !== 'cli') {
    echo "\n[error] this is a CLI script\n";
    exit(1);
}

require_once(dirname(__FILE__) . "/../engine/vendor/autoload.php");
error_reporting(E_ALL);
date_default_timezone_set('UTC');

$_SCRIPTNAME = basename(__FILE__);

array_shift($argv);

if (isset($argv[0]) && $argv[0] == 'help') {
    $help = true;
    array_shift($argv);
} elseif (array_search('--help', $argv)) {
    $help = true;
}

if (!$argv) {
    // TODO: list handlers?
    echo "{$_SCRIPTNAME}: specify a controller\n";
    exit(1);
}

try {
    $handler = Minds\Cli\Factory::build($argv);

    if (!$handler) {
        echo "{$_SCRIPTNAME}: controller `{$argv[0]}` not found\n";
        exit(1);
    } elseif (!($handler instanceof Minds\Interfaces\CliControllerInterface)) {
        echo "{$_SCRIPTNAME}: `{$argv[0]}` is not a controller\n";
        exit(1);
    }

    $minds = new Minds\Core\Minds();
    $minds->loadConfigs();
    $minds->loadLegacy();

    if (isset($help)) {
        $handler->help();
    } else {
        $errorlevel = $handler->exec();
        exit((int) $errorlevel);
    }
} catch (Minds\Exceptions\CliException $e) {
    echo "{$_SCRIPTNAME}: {$e->getMessage()}\n";
    exit(1);
} catch (\Exception $e) {
    $exceptionClass = get_class($e);
    echo "{$_SCRIPTNAME}: [{$exceptionClass}] {$e->getMessage()}\n";
    exit(1);
}

exit(0);
