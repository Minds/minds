<?php
    /**
    * Elgg Membership plugin
    * Membership trail durations page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");

    $trial_period_units = get_input('trial_period_units');
    $trial_duration_pulldown = array();
    if($trial_period_units == 'D') {
        // Unit is D - Period is 7-90
        for($i=7;$i<=90;$i++) {
            $trial_duration_pulldown[$i] = $i;
        }
    } else if($trial_period_units =='W'){
        // Unit is W - Period is 1-52
        for($i=1;$i<=52;$i++) {
            $trial_duration_pulldown[$i] = $i;
        }
    } else if($trial_period_units == 'M'){
        // Unit is M - Period is 1-12
        for($i=1;$i<=12;$i++) {
            $trial_duration_pulldown[$i] = $i;
        }
    } else if($trial_period_units == 'Y'){
        // Unit is Y - Period is 1-5
        for($i=1;$i<=5;$i++) {
            $trial_duration_pulldown[$i] = $i;
        }
    }
    echo elgg_view('input/dropdown' , array('name' => 'params[trial_period_duration]',"options_values"=>$trial_duration_pulldown,'value' => $trial_period_duration,));