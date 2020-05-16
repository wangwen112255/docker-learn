<?php

//
//$server = new Swoole\Websocket\Server("0.0.0.0", 9502);
//
//$server->on('open', function($server, $req) {
//    echo "connection open: {$req->fd}\n";
//});
//
//$server->on('message', function($server, $frame) {
//    echo "received message: {$frame->data}\n";
//    $server->push($frame->fd, json_encode(["hello", "world"]));
//});
//
//$server->on('close', function($server, $fd) {
//
//    echo "connection close: {$fd}\n";
//});
//
//$server->start();


;
//$serv = new Swoole\Server("0.0.0.0", 9502);

//监听连接进入事件
//$serv->on('Connect', function ($serv, $fd) {
//    echo "Client: Connect.\n";
//});
//
////监听数据接收事件
//$serv->on('Receive', function ($serv, $fd, $from_id, $data) {
//    var_dump($data);
//    $serv->send($fd, "Server已经收到您的信息: ".$data);
//});
//
////监听连接关闭事件
//$serv->on('Close', function ($serv, $fd) {
//    echo "Client: Close.\n";
//});
//
////启动服务器
//$serv->start();


//创建Server对象，监听 127.0.0.1:9502端口，类型为SWOOLE_SOCK_UDP
//$serv = new Swoole\Server("0.0.0.0", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
////
//////监听数据接收事件
////$serv->on('Packet', function ($serv, $data, $clientInfo) {
////    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server ".$data);
//////    var_dump($clientInfo);
////});
////
//////启动服务器
////$serv->start();
///
///
/// $http = new Swoole\Http\Server("0.0.0.0", 9501);
//
//$http->on('request', function ($request, $response) {
//    var_dump($request->get, $request->post);
//    $response->header("Content-Type", "text/html; charset=utf-8");
//    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
//});
//
//$http->start();



$http = new Swoole\Http\Server("0.0.0.0", 9501);

$http->on('request', function ($request, $response) {
    if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
        $response->end();
        return;
    }
    var_dump($request->get, $request->post);
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
//
$http->start();
//while(1){
//
//    echo 1;
//
//    sleep(1);
//
//
//}
//echo 'dfas';
//exit();

//多进程管理模块
$pool = new Swoole\Process\Pool(2);
//var_dump('fa');
var_dump($pool);
//让每个OnWorkerStart回调都自动创建一个协程
$pool->set(['enable_coroutine' => true]);
$pool->on("workerStart", function ($pool, $id) {
    //每个进程都监听9501端口
    $server = new Swoole\Coroutine\Server('127.0.0.1', '9501' , false, true);
    //收到15信号关闭服务
    Swoole\Process::signal(SIGTERM, function () use ($server) {
        $server->shutdown();
    });
    //接收到新的连接请求
    $server->handle(function (Swoole\Coroutine\Server\Connection $conn) {
        //接收数据
        $data = $conn->recv();
        if (empty($data)) {
            //关闭连接
            $conn->close();
        }
        //发送数据
        $conn->send("hello");
    });
    //开始监听端口
    $server->start();
});
$pool->start();



