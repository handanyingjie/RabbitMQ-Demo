<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/13 0013
 * Time: 13:02
 */
require_once './vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connect = [
    'host' => 'localhost',
    'port' => '5672',
    'user' => 'guest',
    'password' => 'guest',
];
//队列名称
$queueName = 'hello';

//连接rabbitMQ
$rabbitMQ = new AMQPStreamConnection($connect['host'],$connect['port'],$connect['user'],$connect['password']);

//创建一个频道
$channel = $rabbitMQ->channel();

//指定一个队列
/*
 * queue_declare 参数说明
 * @passive 被动的
 * @durable 是否持久
 * @exclusive 独有的
 * @auto_delete 自动删除
 */
$channel->queue_declare($queueName,false,false,false,false);

//被发送的消息
$msg = new AMQPMessage('this is my first use RabbitMQ!');
//发送消息
/*
 * $message 消息
 * $exchange 路由
 * $routing_key 路由键
 * $mandatory  强制
 * $immediate 立即
 * $ticket 门票
 */
$channel->basic_publish($msg,'',$queueName);
echo "send message<br>";

//关闭频道和连接
$channel->close();
$rabbitMQ->close();