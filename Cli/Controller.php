<?php
namespace Minds\Cli;

class Controller
{
    protected $args = [];
    protected $opts = [];

    private $_execCommand;

    public function out($strings)
    {
        if (!is_array($strings)) {
            $strings = [$strings];
        }

        foreach ($strings as $string) {
            echo $string . PHP_EOL;
        }
    }

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
    }

    public function getExecCommand()
    {
        return $this->_execCommand ?: 'exec';
    }

    public function getOpts(array $opts)
    {
        $result = array_flip($opts);

        array_walk($result, function(&$item, $key) {
            $item = isset($this->opts[$key]) ? $this->opts[$key] : null;
        });

        return $result;
    }
}
