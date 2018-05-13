<?php
/**
 * Created by PhpStorm.
 * User: liuyingjie
 * Date: 2018/5/13 0013
 * Time: 19:44
 */
require_once __DIR__.'/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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

//被发送的消息
$data = implode('',array_slice($argv,1));
if(empty($data)) $data = 'hello world!';
$msg = new AMQPMessage($data,[AMQPMessage::DELIVERY_MODE_PERSISTENT]);

//发送消息到指定交换机
$channel->basic_publish($msg,'logs');

//关闭频道和链接
$channel->close();
$connection->close();