<?php
/**
 * Created by PhpStorm.
 * User: liuyingjie
 * Date: 2018/5/13 0013
 * Time: 19:44
 */
require_once __DIR__.'/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connectConfig = [
    'host' => 'localhost',
    'port' => '5672',
    'user' => 'guest',
    'password' => 'guest',
];

//创建连接
$connection = new AMQPStreamConnection($connectConfig['host'],$connectConfig['port'],$connectConfig['user'],$connectConfig['password']);

//创建频道
$channel = $connection->channel();

//创建交换机
$channel->exchange_declare('logs','fanout',false,false,false);

//创建消息队列
list($queueName,,) = $channel->queue_declare('',false,false,true,false);
$channel->queue_bind($queueName,'logs');

echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";
$function = function($msg){
    echo ' [x] ', $msg->body, "\n";
};
$channel->basic_consume($queueName,'',false,true,false,false,$function);

while (count($channel->callbacks)){
    $channel->wait();
}

$channel->close();
$connection->close();