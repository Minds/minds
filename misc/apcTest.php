<?php

apc_store('test', 'hello');
var_dump(apc_fetch('test'));
