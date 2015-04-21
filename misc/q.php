<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$rmq = Minds\Core\Queue\Client::build("RabbitMQ");

$rmq->setExchange("mindsqueue")
    ->setQueue("Push")
    ->send("push notification");

//setup our send
$rmq->setExchange("topic_demo", "topic")
    ->setQueue("Cluster", "ping.all")
    ->send("ping from mark");

if(php_sapi_name() == 'cli'){
    //setup our reciever
    $rmq->setQueue("demoqueue")->receive(function($data){
         echo $data->body;
    });
        
}