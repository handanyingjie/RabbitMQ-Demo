<?php
/**
 * Created by PhpStorm.
 * User: liuyingjie
 * Date: 2018/5/13 0013
 * Time: 18:04
 */
require_once __DIR__.'/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connectConfig = [
    'host' => 'localhost',
    'port' => '5672',
    'user' => 'guest',
    'password' => 'guest',
];
$queueName = 'task_queue';

//创建一个到RabbitMq的连接
$connection = new AMQPStreamConnection($connectConfig['host'],$connectConfig['port'],$connectConfig['user'],$connectConfig['password']);
$channel = $connection->channel();  //创建一个频道
$channel->queue_declare($queueName,false,true,false,false); //指定被处理的队列

echo '[*] Waiting for messages. To exit press CTRL+C', "\n";

$function = function($msg){
    echo '[x] Received ', $msg->body, "\n";
    sleep(substr_count($msg->body,'.'));
    echo '[x] Done ',"\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null); //在开启多个消费者的情况下,只有当前的消费返回ack确认，才会再次分配下一条任务
$channel->basic_consume($queueName,'',false,false,false,false,$function);   //消费

while (count($channel->callbacks)){
    $channel->wait();
}

$channel->close();
$connection->close();