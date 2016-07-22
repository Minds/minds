<?php
namespace Minds\Cli;

class Controller
{
    protected $args = [];
    protected $opts = [];

    private $_app;
    private $_execCommand;

    // Output flags
    const OUTPUT_INLINE = 0b10;
    const OUTPUT_PRE = 0b01;

    public function out($strings, $flags = 0)
    {
        if (!is_array($strings)) {
            $strings = [ $strings ];
        }

        if ($flags & static::OUTPUT_PRE) {
            array_unshift($strings, '');
        }

        $i = -1;
        foreach ($strings as $string) {
            $i++;
            $eol = PHP_EOL;

            if (($flags & static::OUTPUT_INLINE) && count($strings) == $i + 1) {
                $eol = ' ';
            }

            echo $string . $eol;
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

    public function setApp($app = null)
    {
        $this->_app = $app;
    }

    public function getApp()
    {
        return $this->_app;
    }

    public function getExecCommand()
    {
        return $this->_execCommand ?: 'exec';
    }

    public function getOpt($key)
    {
        if (!$key) {
            return null;
        }

        return isset($this->opts[$key]) ? $this->opts[$key] : null;
    }

    public function getOpts(array $opts)
    {
        $result = array_flip($opts);

        array_walk($result, function(&$item, $key) {
            $item = $this->getOpt($key);
        });

        return $result;
    }

    public function getAllOpts()
    {
        return $this->opts;
    }
}
