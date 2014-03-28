<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

phpmailer_send(
                        "access@minds.com",
                        elgg_get_site_entity()->name,
                        "mark@minds.com",
                        '',
                        "test",
                        "test");
