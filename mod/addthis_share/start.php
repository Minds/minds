<?php

/* ***********************************************************************
 * Addthis-Share 1.2
 * Updated: 22nd January 2012
 * 
 * Author: Aung Aung <http://community.elgg.org/pg/profile/aung>
 * copyright(c) 2012 Aung
 * ***********************************************************************/

elgg_register_event_handler('init', 'system', 'addthis_share_init');
 
    function addthis_share_init()
    {
         elgg_extend_view('object/elements/summary', 'addthis_share/addthis');
    }
 
?>