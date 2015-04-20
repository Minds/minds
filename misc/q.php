<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$rmq = Minds\Core\Queue\Client::build("RabbitMQ");

//setup our send
$rmq->setQueue("demoqueue")->send(function(){});

if(php_sapi_name() == 'cli'){
    //setup our reciever
    $rmq->setQueue("demoqueue")->receive(function($data){
         echo $data->body;
    });
        
}