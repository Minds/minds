<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor','lib','classes'])
    ->in(__DIR__);

return PhpCsFixer\Config::create()
      ->fixers(['psr2', 'strict_param', 'short_array_syntax', 'no_blank_lines_after_class_opening'])
      ->finder($finder);
