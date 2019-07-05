<?php

namespace Minds\Cli;

use ReflectionClass;
use ReflectionMethod;
use Minds\Exceptions\CliException;

/**
 * CLI Controller.
 */
class Controller
{
    protected $args = [];
    protected $opts = [];

    private $_app;
    private $_execCommand;

    // Output flags
    const OUTPUT_INLINE = 0b10;
    const OUTPUT_PRE = 0b01;

    /**
     * Echoes a string to standard output.
     *
     * @param string|array $strings a single line string, or an array of lines
     * @param int          $flags   Optional. Output flags (static::OUTPUT_PRE, static::OUTPUT_INLINE).
     */
    public function out($strings, $flags = 0)
    {
        if (!is_array($strings)) {
            $strings = [$strings];
        }

        if ($flags & static::OUTPUT_PRE) {
            array_unshift($strings, '');
        }

        $i = -1;
        foreach ($strings as $string) {
            ++$i;
            $eol = PHP_EOL;

            if (($flags & static::OUTPUT_INLINE) && count($strings) == $i + 1) {
                $eol = ' ';
            }

            echo $string.$eol;
        }
    }

    /**
     * Sets the flags and/or arguments for the current operation. If the first argument
     * matches a public controller method name, that will be executed instead of exec() and
     * ignored from arguments list.
     *
     * @param array $args
     */
    public function setArgs(array $args)
    {
        $optsAllowed = true;

        foreach ($args as $arg) {
            if (strpos($arg, '--') === 0 && $optsAllowed) {
                if ($arg === '--') {
                    $optsAllowed = false;
                    continue;
                }

                $arg = explode('=', substr($arg, 2), 2);

                if (!$arg) {
                    continue;
                }

                $this->opts[$arg[0]] = isset($arg[1]) ? $arg[1] : true;
            } elseif (!$this->_execCommand && count($this->args) === 0 && method_exists($this, $arg)) {
                $this->_execCommand = $arg;
            } else {
                $this->args[] = $arg;
            }
        }

        return $this;
    }

    /**
     * Sets the current app for the current operation.
     *
     * @param Core\Minds $app Current app instance
     */
    public function setApp($app = null)
    {
        $this->_app = $app;
    }

    /**
     * Gets the current app.
     *
     * @return Core\Minds|null
     */
    public function getApp()
    {
        return $this->_app;
    }

    /**
     * Gets the method to be executed. Setted on $this->setArgs().
     *
     * @return string
     */
    public function getExecCommand()
    {
        return $this->_execCommand ?: 'exec';
    }

    /**
     * Gets a single option value. Null if not found.
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getOpt($key)
    {
        if (!$key) {
            return null;
        }

        return isset($this->opts[$key]) ? $this->opts[$key] : null;
    }

    /**
     * Gets an array of passed keys from options. Null if not found.
     *
     * @param array $opts Keys to be read
     *
     * @return array
     */
    public function getOpts(array $opts)
    {
        $result = array_flip($opts);

        array_walk($result, function (&$item, $key) {
            $item = $this->getOpt($key);
        });

        return $result;
    }

    /**
     * Gets an array with all options passed.
     *
     * @return array
     */
    public function getAllOpts()
    {
        return $this->opts;
    }

    public function gatekeeper($message = '')
    {
        $this->out(trim("{$message} Start operation?"), $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new CliException('Cancelled by user');
        }
    }

    /**
     * Gets the list of publically available commands and filters out the system ones.
     */
    public function getCommands()
    {
        $excludedMethods = ['__construct', 'help', 'out', 'setArgs', 'setApp', 'getApp', 'getExecCommand', 'getOpt', 'getOpts', 'getAllOpts', 'getCommands', 'displayCommandHelp'];
        $commands = [];
        foreach ((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $commands[] = $method->getName();
        }

        return array_diff($commands, $excludedMethods);
    }

    public function displayCommandHelp()
    {
        $this->out('Available commands:');
        $this->out(implode(', ', $this->getCommands()));
    }
}
