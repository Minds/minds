<?php
   global $CONFIG;
   $url = $CONFIG->wwwroot;

?>
/**
 * Beechat
 * 
 * @package beechat
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Beechannels <contact@beechannels.com>
 * @copyright Beechannels 2007-2010
 * @link http://beechannels.com/
 */

div#beechat {
    display: block !important;
    display: none;
    position: fixed;
    left: 1%;
    right: 1%;
    bottom: 0;
    margin: 0;
    padding: 0;
    height: 20px;
    z-index: 999;

    font-family: Arial, Helvetica, "Liberation Sans", FreeSans, sans-serif;
    font-size: 0.9em;
    color: #222222;
    background-color: #DDDDDD;
    border-top: 1px solid #BBBBBB;
    border-left: 1px solid #BBBBBB;
}
div#beechat a img {
    border: none;
}
div#beechat a {
    text-decoration: none;
}
div#beechat img {
    vertical-align: middle;
}
div#beechat a:hover {
    text-decoration: underline;
}
.beechat_control {
    cursor: pointer;
    color: #CCCCFF;
    font-size: 1.6em;
}
.beechat_control:hover {
    color: white;
}
.beechat_box_control {
    cursor: pointer;
    color: #888888;
    font-size: 1em;
}
.beechat_box_control:hover {
    color: #222222;
}
.beechat_chatbox_control {
    cursor: pointer;
    color: #CCCCFF;
    font-size: 1.6em;
}
.beechat_chatbox_control:hover {
    color: white;
}
.beechat_label {
    font-weight: bold;
    font-size: 1.1em;
}

/*
** -
** left side
** -
*/
div#beechat_left {
    position: absolute;
    top: 0;
    left: 0;
    width: 116px;
    height: 18px;
    margin: 0;
    padding: 1px 2px;
}


/*
** -
** right side
** -
*/
div#beechat_right {
    position: absolute;
    top: 0;
    right: 0;
    width: 220px;
    height: 20px;
    margin: 0;
    padding: 0 0 0 0;

    border-left: 1px solid #BBBBBB;
    border-right: 1px solid #BBBBBB;
}
div#beechat_contacts {
    position: absolute;
    right: 0px;
    bottom: 0;
    width: 222px;
    height: 240px;
    margin: 0 auto 20px auto;
    padding: 0;
    display: none;

    background-color: white;
}
div#beechat_contacts_top {
    color: white;
    background-color: #193C60;
    width: 220px;
    height: 32px;

    border-top: 1px solid #0B2C4F;
    border-left: 1px solid #0B2C4F;
    border-right: 1px solid #0B2C4F;
}
div#beechat_contacts_top .beechat_label {
    float: left;
    height: 20px;
    padding: 6px;
}
div#beechat_contacts_controls {
    margin: 0;
    padding: 0;
}
div#beechat_contacts_controls span#beechat_contacts_control_minimize {
    position: relative;
    top: -7px;
    float: right;
    display: block;
    width: 20px;
    height: 20px;
    padding: 2px;

    font-size: 1.6em;
    font-weigth: bold;
    text-align: center;
}
span#beechat_contacts_button {
    display: block;
    width: 190px;
    padding: 2px 6px 0 24px;
    height: 18px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: normal;

    background-image: url('<?php echo $url; ?>mod/beechat/graphics/icons/statuses.png');
}
span#beechat_contacts_button.online {
    background-position: 4px -750px;
    background-repeat: no-repeat;
}
span#beechat_contacts_button.dnd {
    background-position: 4px -796px;
    background-repeat: no-repeat;
}
span#beechat_contacts_button.away {
    background-position: 4px -842px;
    background-repeat: no-repeat;
}
span#beechat_contacts_button.offline {
    background-position: 4px -888px;
    background-repeat: no-repeat;
}
span#beechat_contacts_button:hover {
    background-color: white;
}
div#beechat_availability_switcher {
    width: 218px;
    height: 24px;
    margin: 0;
    padding: 0 0 0 2px;

    background-color: #EEEEEE;
    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    border-bottom: 1px solid #BBBBBB;
}
span#beechat_current_availability {
    float: left;
    padding: 4px 4px 4px 22px;

    font-weight: bold;
    cursor: pointer;
}
span#beechat_current_availability:hover {
    text-decoration: underline;
}
span#beechat_availability_switcher_control {
    float: right;
    width: 24px;
    height: 20px;
    cursor: pointer;
}
span.beechat_availability_switcher_control_up {
    background: no-repeat 50% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_arrow_up.png');
}
span.beechat_availability_switcher_control_down {
    background: no-repeat 50% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_arrow_down.png');
}
ul#beechat_availability_switcher_list {
    display: none;
    padding:0;
    margin:0;
    list-style:none;
}
ul#beechat_availability_switcher_list li {
    margin: 0;
    padding: 4px 4px 4px 24px;

    cursor: pointer;
}
ul#beechat_availability_switcher_list li:hover {
    background-color: #EEEEEE;
}
div#beechat_contacts_content {
    width: 220px;
    height: 164px;
    overflow: auto;

    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    background-color: white;
}
ul#beechat_contacts_list {
    background-color: white;
    padding:0;
    margin:0;
    list-style:none;
}
ul#beechat_contacts_list li img {
    margin: 0 4px 0 0;
    width: 25px;
    height: 25px;
}
ul#beechat_contacts_list li {
    margin: 0;
    padding: 4px 4px 4px 6px;

    cursor: pointer;
    color: #333;
}
ul#beechat_contacts_list li:hover {
    background-color: #F5F6F8;
    color: #333;
}
div#beechat_contacts_bottom {
    width: 220px;
    height: 18px;

    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
}
span#beechat_contacts_bottom_bar {
    position: absolute;
    display: block;
    bottom: 0;
    width: 210px;
    height: 1px;

    background-color: #BBBBBB;
    margin: auto 4px;
}


/*
** -
** center area
** -
*/
div#beechat_center {
    float: right;
    display: block;
    width: 586px;
    height: 20px;
    margin: 0 220px 0 100px;
    *margin: 0 312px 0 100px;
    padding: 0;
}
div#beechat_center .next, div#beechat_center .prev {
    display: none;

    border-left: 1px solid #BBBBBB;
    cursor: pointer;
}
div#beechat_center .next {
    position: absolute;
    right: 220px;
    width: 24px;
    height: 20px;

    background: no-repeat 50% url("<?php echo $url; ?>mod/beechat/graphics/icons/resultset_next.png");
}
div#beechat_center .prev {
    position: absolute;
    right: 872px;
    width: 24px;
    height: 20px;

    background: no-repeat 50% url("<?php echo $url; ?>mod/beechat/graphics/icons/resultset_previous.png");
}
div#beechat_scrollboxes {
    float: right;
    overflow: hidden;
    width: 628px;
    height: 21px;
    margin: 0 24px 0 24px;
    text-align: left;
}
div#beechat_scrollboxes ul {
    width: 200000em;
    list-style: none;
    padding:0;
    margin:0;
}
div#beechat_scrollboxes ul li {
    float: right;
    display: block;
    width: 130px;
    height: 20px;
    padding: 1px 0 0 22px;

    cursor: pointer;
    border-left: 1px solid #BBBBBB;
}
div#beechat_scrollboxes ul li:hover {
    color: #000000;
    background-color: white;
}
div#beechat_scrollboxes ul li.beechat_scrollbox_selected {
    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    background-color: white;
}
div#beechat_scrollboxes ul li span.beechat_unread_count {
    float: right;
    display: block;
    width: 16px;
    height: 14px;
    padding-top: 2px;
    margin: 0 6px 0 0;

    text-align: center;
    font-size: 0.7em;
    color: white;
    background: no-repeat 0% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/notification_pink.png');
}
div#beechat_scrollboxes ul li span#beechat_box_control_close {
    float: right;
    width: auto;
    padding: 1px 4px;
    height: 20px;
}

/*
** --
** availability classes
** --
*/
.beechat_left_availability_chat {
    background: no-repeat 2% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_green.png');
}
.beechat_left_availability_dnd {
    background: no-repeat 2% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_delete.png');
}
.beechat_left_availability_away {
    background: no-repeat 2% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_orange.png');
}
.beechat_left_availability_xa {
    background: no-repeat 2% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_red.png');
}
.beechat_left_availability_offline {
    background: no-repeat 2% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_black.png');
}


.beechat_right_availability_chat {
    background: no-repeat 96% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_green.png');
}
.beechat_right_availability_dnd {
    background: no-repeat 96% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_delete.png');
}
.beechat_right_availability_away {
    background: no-repeat 96% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_orange.png');
}
.beechat_right_availability_xa {
    background: no-repeat 96% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_red.png');
}
.beechat_right_availability_offline {
    background: no-repeat 96% 50% url('<?php echo $url; ?>mod/beechat/graphics/icons/bullet_black.png');
}

/*
** --
** tooltips
** --
*/
div.tooltip.tooltipchat {
    display: none;
    padding: 4px;
    width: auto;
    background: transparent no-repeat left bottom url('<?php echo $url; ?>mod/beechat/graphics/icons/pointer.png');
}
div.tooltip.tooltipchat h3 {
    margin: 0;
    padding: 4px;

    font-weight: normal;
    font-size: 0.9em;
    color: white;
    background-color: #222222;
}


/*
** --
** chatboxes
** --
*/
div.beechat_chatbox {
    position: absolute;
    width: 240px;
    height: 300px;
    bottom: 25px;
    margin: 0;
    padding: 0;

    background-color: #DDDDDD;
}
div.beechat_chatbox a {
    color: white;
}
div.beechat_chatbox a:hover {
    text-decoration: underline;
}
div.beechat_chatbox_top {
    width: 238px;
    height: 24px;
    margin: 0;
    padding: 0;

    font-size: 0.9em;
    color: white;
    background-color: #193C60;
    border-top: 1px solid #0B2C4F;
    border-left: 1px solid #0B2C4F;
    border-right: 1px solid #0B2C4F;
}
div.beechat_chatbox_top .beechat_chatbox_top_icon {
    position: absolute;
    top: 4px;
    left: 4px;
    z-index: 2;

    widht: 40px;
    height: 40px;
}
div.beechat_chatbox_top .beechat_label {
    float: left;
    height: 13px;
    padding: 4px 6px 6px 6px;

    margin-left: 54px;
}
div.beechat_chatbox_top_controls {
    margin: 0;
    padding: 0;
}
div.beechat_chatbox_top_controls .beechat_chatbox_control {
    float: right;
    display: block;
    width: 20px;
    height: 19px;
    padding: 2px;
    margin: 0;

    font-size: 1.2em;
    font-weight: bold;
    text-align: center;
}
div.beechat_chatbox_subtop {
    width: 172px;
    height: 30px;
    padding: 2px 6px 2px 60px;

    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    border-bottom: 1px solid #CCCCCC;
    background-color: #DDDDDD;
}
div.beechat_chatbox_content {
    width: 238px;
    height: 202px;
    margin: 0;
    padding: 0;
    overflow: auto;

    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    background-color: white;
}
div.beechat_chatbox_content div.beechat_chatbox_message {
    width: auto;
    height: auto;
    margin: 0;
    padding: 2px;
    border-top: 1px solid #DDDDDD;
}
div.beechat_chatbox_message span.beechat_chatbox_message_sender {
    position: relative;
    top: 0;
    left: 6px;
    font-weight: bold;
    font-size: 1em;
}
div.beechat_chatbox_message span.beechat_chatbox_message_date {
    float: right;
    margin: 0 6px 0 0;
}
div.beechat_chatbox_content a {
    color: #003399;
}
div.beechat_chatbox_content a:hover {
    text-decoration: underline;
}
div.beechat_chatbox_content p {
    margin: 0;
    padding: 2px 6px;
}
div.beechat_chatbox_content p.beechat_chatbox_state {
    font-size: 1em;
    color: #888888;
}
div.beechat_chatbox_input {
    width: 238px;
    height: 40px;
    margin: 0;
    padding: 0;

    border-top: 2px solid #BBBBBB;
    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    background-color: #DDDDDD;
}
div.beechat_chatbox_input textarea {
    width: 204px;
    height: 32px;
    max-width: 240px;
    max-height: 40px;
    padding: 4px 4px 4px 30px;
    margin: auto;
    overflow: hidden;
    vertical-align: top;
    resize: none;

    font-size: 1em;
    font-family: Arial, Helvetica, "Liberation Sans", FreeSans, sans-serif;
    outline: none;
    border: none;
    background: white no-repeat 4px 3px url('<?php echo $url; ?>mod/beechat/graphics/icons/chat_icon.png');
}
div.beechat_chatbox_input textarea:focus {
    outline: none;
    border: none;
    background-color: white;
}
div.beechat_chatbox_bottom {
    position: absolute;
    width: 238px;
    height: 1px;

    background-color: white;
    border-left: 1px solid #666666;
    border-right: 1px solid #666666;
    border-bottom: 1px solid #666666;
    z-index: 2;
}
div.beechat_chatbox_bottom span {
    position: absolute;
    display: block;
    right: 0;
    top: 0;
    width: 152px;
    height: 1px;

    border-bottom: 1px solid white;
}
div.beechat_chatbox_bottom span span {
    position: absolute;
    display: block;
    width: 146px;
    height: 1px;
    right: 4px;
    top: 0;

    border-bottom: 1px solid #BBBBBB;
}
