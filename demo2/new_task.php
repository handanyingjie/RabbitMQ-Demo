<?php
/**
 * Created by PhpStorm.
 * User: liuyingjie
 * Date: 2018/5/13 0013
 * Time: 18:03
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
$queueName = 'task_queue';

//创建RabbitMq连接
$connection = new AMQPStreamConnection($connectConfig['host'],$connectConfig['port'],$connectConfig['user'],$connectConfig['password']);
$channel = $connection->channel();  //创建一个频道
$channel->queue_declare($queueName,false,true,false,false); //指定一个队列

$data = implode('',array_slice($argv,1));   //获取命令行输入的消息
if(empty($data)) $data = "hello world!";
$msg = new AMQPMessage($data,['delivery_model' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);  //创建一个持久化的消息

$channel->basic_publish($msg,'',$queueName);    //发送消息
echo "[x] Sent ", $data, "\n";

//关闭频道和连接
$channel->close();
$connection->close();
