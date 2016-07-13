<?php
/**
 * Minds Cli controller interface.
 */
namespace Minds\Interfaces;

interface CliControllerInterface
{
    public function help();
    public function exec();
    public function setArgs(array $args);
}
