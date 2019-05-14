Swoole结合TP5

项目启动->php public/index.php (目前只是用php xxx.php模式启动服务)

项目类库在 application/common/lib

Task.php负责任务的分发
Message.php 负责消息处理
Predis.php 进行相关的redis操作

静态文件在public/static下面
启动服务后即可用 IP:PORT/chat.html连接(最基础的公共聊天demo)

项目修改了TP5的路由规则 以适配 域名/模块/控制器/方法 访问模式,
目前有时间会继续更新.