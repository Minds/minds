<?php

namespace Minds\Controllers\Cli\Plugins;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;

class Create extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        $this->out('Creating plugin from template', $this::OUTPUT_PRE);

        //copy the template
        $dir = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/plugins/_template';
        $files = $this->getFiles($dir);

        $plugin = [
            "name" => $this->getOpt('name'),
            "author" => $this->getOpt('author') ?: "Minds"
        ];

        if(!$plugin['name']){
            throw new Exceptions\CliException("You must pass the --name option");
        }

        if(is_dir(str_replace('_template', $plugin['name'], $dir)) && !$this->getOpt('force')){
            throw new Exceptions\CliException("A plugin called {$plugin['name']} currently exists. Use the --force flag to replace");
        }

        foreach($files as $file){
            $contents = file_get_contents($file);

            $filename = str_replace('_template', $plugin['name'], $file);
            $parts = explode('/', $filename);
            array_pop($parts);
            $parentDir = implode('/', $parts);
            if(!is_dir($parentDir)) {
                mkdir($parentDir, 0777, true);
            }

            //replace {{plugin.}}
            $contents = str_replace('{{plugin.name}}', $plugin['name'], $contents);
            $contents = str_replace('{{plugin.author}}', $plugin['author'], $contents);
            $contents = str_replace('{{plugin.lc_name}}', strtolower($plugin['name']), $contents);

            file_put_contents($filename, $contents);
        }


    }

    private function getFiles($dir)
    {
        $d = new \RecursiveDirectoryIterator($dir,\RecursiveDirectoryIterator::SKIP_DOTS);

        $return = [];
        foreach(new \RecursiveIteratorIterator($d) as $file) {
            array_push($return, $file);

        }

        return $return;
    }
}
