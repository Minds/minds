<?php
/**
 * Minds Cli controller interface.
 */
namespace Minds\Interfaces;

interface CliControllerInterface
{
    public function help($command = null);
    public function exec();
    public function setArgs(array $args);
    public function getExecCommand();
}
