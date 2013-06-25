/*
 * Project Name:            Sociable Theme
 * Project Description:     Theme for Elgg 1.8
 * Author:                  Shane Barron - SocialApparatus
 * License:                 GNU General Public License (GPL) version 2
 * Website:                 http://socia.us
 * Contact:                 sales@socia.us
 * 
 * File Version:            1.0
 * Last Updated:            5/11/2013
 */

$(document).ready(function() {
    $(".elgg-button").addClass('btn').removeClass('elgg-button');
    //$(".elgg-button-submit").addClass('btn-success').removeClass("elgg-button-submit");
    $(".elgg-state-selected").addClass("active");
    $(".socia_login").click(function(e) {
        e.preventDefault();
        $("#sociaLogin").modal();
    });
    $(".socia_register").click(function(e) {
        e.preventDefault();
        $("#sociaRegister").modal();
    });
    $(".elgg-page").fadeIn('fast');
    $(".loader").hide();
});