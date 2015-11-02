<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude(['vendor','lib','classes'])
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()
      ->fixers(['psr2', 'strict_param', 'short_array_syntax', 'no_blank_lines_after_class_opening'])
      ->finder($finder);
