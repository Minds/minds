<?php
     /**
    * Elgg Membership plugin
    * Membership checkout radio input
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    $defaults = array(
            'align' => 'vertical',
            'value' => array(),
            'disabled' => false,
            'options' => array(),
            'name' => '',
    );

    $vars = array_merge($defaults, $vars);

    $id = '';
    if (isset($vars['id'])) {
            $id = "id=\"{$vars['id']}\"";
            unset($vars['id']);
            unset($vars['internalid']);
    }

    $class = "elgg-input-radios elgg-{$vars['align']}";
    if (isset($vars['class'])) {
            $class .= " {$vars['class']}";
            $class_radio .= " {$vars['class']}";
            unset($vars['class']);
    }
    unset($vars['align']);
    $vars['class'] = 'elgg-input-radio';
    $vars['class'] .= $class_radio;
    if (is_array($vars['value'])) {
            $vars['value'] = array_map('elgg_strtolower', $vars['value']);
    } else {
            $vars['value'] = array(elgg_strtolower($vars['value']));
    }

    $options = $vars['options'];
    unset($vars['options']);

    $value = $vars['value'];
    unset($vars['value']);

    if ($options && count($options) > 0) {
            echo "<ul class=\"$class\" $id>";
            foreach ($options as $label => $option) {

                    $vars['checked'] = in_array(elgg_strtolower($option), $value);
                    $vars['value'] = $option;

                    $attributes = elgg_format_attributes($vars);

                    // handle indexed array where label is not specified
                    // @deprecated 1.8 Remove in 1.9
                    if (is_integer($label)) {
                            elgg_deprecated_notice('$vars[\'options\'] must be an associative array in input/radio', 1.8);
                            $label = $option;
                    }

                    echo "<li><input type=\"radio\" $attributes />$label</li>";
            }
            echo '</ul>';
    }
