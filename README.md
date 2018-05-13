# RabbitMQ-Demo
学习用

window下安装RabbitMQ
一 下载安装环境
1.1下载 Erlang
下载地址http://www.erlang.org/downloads

1.2 安装Erlang
1.2.1运行Erlang
运行otp_win64_20.3.exe
1.2.2修改环境变量
1) 添加系统环境变量ERLANG_HOME，值为安装目录.
2) 修改系统环境变量Path,在PATH变量中添加“%ERL_HOME%\bin”
3) 重启电脑后，在控制台输入 erl,如果出现类似“Eshell V6.1 (abort with ^G)”字样，说明安装成功。

1.3 安装 RabbitMQ
1.3.1 运行安装
下载rabbitmq-server-3.6.6.exeWindows版本
运行安装rabbitmq-server-3.6.6.exe

1.3.2 运行服务
1 rabbitMq默认自启动
可以修改rabbitmq的配置文件，也可以用默认配置运行。在开始菜单栏里可以看到运行指令reinstall/remove/start/stop

2 进入 RabbitMQ的安装目录(管理员身份)
运行 ./rabbitmq-service.bat


1.3.4 默认用户访问
该代理创建了一个默认的账号“guest”密码“guest”的访问账号。未配置的客户端默认使用该账号凭据，但仅限于访问本地。如果是网络访问则需要另外配置。其他用户访问参考访问控制文档
1.3.5 管理服务
使用rabbitmqctl 管理服务，参见官方文档
http://www.rabbitmq.com/man/rabbitmqctl.1.man.html
 -n node 默认node名称是"rabbit@server"，如果你的主机是'server.example.com',那么node名是rabbit@server
 -q 安静输出模式，信息会被禁止输出
rabbitmqctl [-n node] [-t timeout] [-q] {command} [command options...]

1.3.5.1 基本管理功能
stop [<pid_file>]  
    #停止在erlang node上运行的rabbitmq，会使rabbitmq停止
stop_app   
    #停止erlang node上的rabbitmq的应用，但是erlang node还是会继续运行的
start_app   
    #启动erlan node上的rabbitmq的应用
wait <pid_file>  
    #等待rabbitmq服务启动
reset  
    #初始化node状态，会从集群中删除该节点，从管理数据库中删除所有数据，例如vhosts等等。在初始化之前rabbitmq的应用必须先停止
force_reset  
    #无条件的初始化node状态
rotate_logs <suffix>   
    #轮转日志文件

1.3.5.2 cluster管理
join_cluster <clusternode> [--ram]  
    #clusternode表示node名称，--ram表示node以ram node加入集群中。默认node以disc node加入集群，在一个node加入cluster之前，必须先停止该node的rabbitmq应用，即先执行stop_app。
cluster_status  
    #显示cluster中的所有node
change_cluster_node_type disc | ram  
    #改变一个cluster中node的模式，该节点在转换前必须先停止，不能把一个集群中唯一的disk node转化为ram node
forget_cluster_node [--offline]  
    #远程移除cluster中的一个node，前提是该node必须处于offline状态，如果是online状态，则需要加--offline参数。
update_cluster_nodes clusternode   
    #
sync_queue queue  
    #同步镜像队列
cancel_sync_queue queue    
    #
1.3.5.3 用户管理
add_user <username> <password>  
    #在rabbitmq的内部数据库添加用户
delete_user <username>  
    #删除一个用户
change_password <username> <newpassword>  
    #改变用户密码  \\改变web管理登陆密码
clear_password <username> 
    #清除用户密码，禁止用户登录
set_user_tags <username> <tag> ...
    #设置用户tags
list_users  
    #列出用户
add_vhost <vhostpath>  
    #创建一个vhosts
delete_vhost <vhostpath>  
    #删除一个vhosts
list_vhosts [<vhostinfoitem> ...]  
    #列出vhosts
set_permissions [-p <vhostpath>] <user> <conf> <write> <read>  
    #针对一个vhosts 给用户赋予相关权限
clear_permissions [-p <vhostpath>] <username>  
    #清除一个用户对vhosts的权限
list_permissions [-p <vhostpath>]   
    #列出哪些用户可以访问该vhosts
list_user_permissions <username>  
    #列出该用户的访问权限
set_parameter [-p <vhostpath>] <component_name> <name> <value>
    #
clear_parameter [-p <vhostpath>] <component_name> <key>
    #
list_parameters [-p <vhostpath>]
    #

1.3.5.4 policy管理
policy管理，策略用来控制和修改queues和exchange在集群中的行为，策略可以应用到vhost
set_policy [-p <vhostpath>] [--priority <priority>] [--apply-to <apply-to>]  
<name> <pattern> <definition>    
    #name 策略名称，pattern  正则表达式，用来匹配资源，符合的就会应用设置的策略，apply-to 表示策略应用到什么类型的地方，一般有queues、exchange和all，默认是all。priority 是个整数优先级，definition 是json格式设置的策略。
clear_policy [-p <vhostpath>] <name>  
    #清除一个策略
list_policies [-p <vhostpath>]  
    #列出已有的策略

1.3.5.5 queue&exchange状态信息
list_queues [-p <vhostpath>] [<queueinfoitem> ...]  
    #返回queue的信息，如果省略了-p参数，则默认显示的是"/"vhosts的信息。
list_exchanges [-p <vhostpath>] [<exchangeinfoitem> ...]  
    #返回exchange的信息。
list_bindings [-p <vhostpath>] [<bindinginfoitem> ...] 
    #返回绑定信息。
list_connections [<connectioninfoitem> ...]  
    #返回链接信息。
list_channels [<channelinfoitem> ...]  
    #返回目前所有的channels。
list_consumers [-p <vhostpath>]  
    #返回consumers，
status  
    #显示broker的状态
environment  
    #显示环境参数的信息
report  
    #返回一个服务状态report，
eval <expr>

1.3.6插件管理
rabbitmq支持各种插件，开启插件可以使用rabbitmq-plugins命令

重启服务


如图，启动插件后重启服务，在浏览器打开http://localhost:15672
登录，用户名密码都是guest


1.3.7 服务日志
服务日志记录在RABBITMQ_NODENAME.log,在文件夹RABBITMQ_LOG_BASE中。其他的日志记录在RABBITMQ_NODENAME-sasl.log.

rabbitmqctl rotate_logs {stuffix}
指示RabbitMQ node循环日志文件.

RabbitMQ 中间件会将原来日志文件中的内容追加到原始名称和后辍的日志文件中，然后再将原始日志文件内容复制到新创建的日志上。实际上，当前日志内容会移到以此后辍结尾的文件上。当目标文件不存在时，将会进行创建。如果不指定后辍，则不会发生循环，日志文件只是重新打开。

rabbitmqctl rotate_logs.1
此命令指示RabbitMQ node将日志文件的内容追加到新日志文件（文件名由原日志文件名和.1后辍构成）中。如. rabbit@mymachine.log.1 和 rabbit@mymachine-sasl.log.1. 最后, 日志会在原始位置恢复到新文件中.
