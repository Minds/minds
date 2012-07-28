<?php
   /**
    * Elgg Membership plugin
    * Membership Subscription duration Page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
    $subscr_period_units = get_input('subscr_period_units');
    $subscr_duration_pulldown = array();
    if($subscr_period_units == 'D') {
        // Unit is D - Period is 7-90
        for($i=7;$i<=90;$i++) {
            $subscr_duration_pulldown[$i] = $i;
        }
    } else if($subscr_period_units == 'W'){
        // Unit is W - Period is 1-52
        for($i=1;$i<=52;$i++) {
            $subscr_duration_pulldown[$i] = $i;
        }
    } else if($subscr_period_units == 'M'){
        // Unit is M - Period is 1-12
        for($i=1;$i<=12;$i++) {
            $subscr_duration_pulldown[$i] = $i;
        }
    } else if($subscr_period_units == 'Y'){
        // Unit is Y - Period is 1-5
        for($i=1;$i<=5;$i++) {
            $subscr_duration_pulldown[$i] = $i;
        }
    }
    echo elgg_view('input/dropdown' , array('name' => 'params[trial_period_duration]',"options_values"=>$subscr_duration_pulldown,'value' => $subscr_period_duration,));