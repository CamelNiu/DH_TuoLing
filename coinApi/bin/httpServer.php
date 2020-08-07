<?php

require "../init.php";


use Swoole\Http\Server as swhttpServer;

/**
 *
 */
class httpServer
{

    private $config;
    private $host;
    private $port;
    private $ws;
    private $event = [
        'connect' => "onConnect",
        'request' => 'onRequest',
    ];
    private $url = "https://api.huobi.pro/market/detail/merged";

    function __construct($config)
    {
        $this->config = $config;
        $this->init();
        $this->registerEvent();
    }


    private function init()
    {
        $this->host = $this->config['http']['host'];
        $this->port = $this->config['http']['port'];
        $this->ws = new swhttpServer($this->host,$this->port);
    }

    private function registerEvent()
    {
        foreach($this->event as $key => $val){
            $this->ws->on($key,[$this,$val]);
        }
    }

    public function onConnect(swhttpServer $server, int $fd, int $reactorId)
    {


    }

    public function onRequest($request, $response)
    {
        $server = $request->server;
        //log::WL(json_encode($server),'curlLog');
        $symbol = $request->get;
        $coinInfo = $this->getCoinData($symbol);
        $response->end(json_encode($coinInfo));
    }

    public function getCoinData($symbol)
    {
        $url = $this->url."?symbol=".$symbol['symbol'];
        $res = curl::get($url);
        $res = json_decode($res,true);
        return $res;
    }

    public function onClose(swhttpServer $server, $fd)
    {
        echo "连接断开 \n";
    }

    public function start()
    {
        $this->ws->start();
    }

}






$ser = new httpServer($config);
$ser->start();



