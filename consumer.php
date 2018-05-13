<?php
set_time_limit(0);
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/13 0013
 * Time: 13:02
 */
require_once './vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connect = [
    'host' => '127.0.0.1',
    'port' => '5672',
    'user' => 'guest',
    'password' => 'guest',
    'vhost' => '/'
];
$channelKey = 'test_channel';
//队列名称
$queueName = 'hello';
$connection = new AMQPStreamConnection($connect['host'],$connect['port'],$connect['user'],$connect['password']);
$channel = $connection->channel();

//声明队列，主要为了防止消息接收者先运行此程序，队列还不存在时创建队列
$channel->queue_declare($queueName,false,false,false,false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$function = function($msg){
    echo $msg->body."\n";
    // 此处手动ack,则创建队列消费者的no_ack参数必须为false
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};
//创建队列消费者
$channel->basic_consume($queueName,"",false, false, false, false, $function);
while (count($channel->callbacks)) {
    $channel->wait();
}
//关闭频道和连接
$channel->close();
$rabbitMQ->close();