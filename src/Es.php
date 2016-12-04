<?php

namespace Jaeger;

use GuzzleHttp\Client;

class Es
{
    private $config = array(
        //服务器地址
        'server_url'=>'http://localhost:9200',
        //索引
        'index' => '',
        //类型
        'type' => ''
    );
    private $http;

    public function __construct($config)
    {
        $this->config = array_merge($this->config,$config);
        $this->http = new Client();
    }

    private function makeRequestUrl($f = null)
    {
        $url = sprintf('%s/%s/%s',$this->config['server_url'],$this->config['index'],$this->config['type']);
        $f && $url = sprintf('%s/%s',$url,$f);
        return $url;
    }

    public function search($data)
    {
        $f = '_search';
        if(is_array($data))
        {
            $data = json_encode($data);
        }elseif(is_string($data) && !EsTools::isJson($data))
        {
            $f .= '?q='.$data;
            $data = '';
        }
        return $this->request('GET',$f,$data);
    }


    private function request($method,$f,$data)
    {
        $url = $this->makeRequestUrl($f);
        $response = $this->http->request($method,$url,[
            'body' => $data
        ]);
        return json_decode($response->getBody(),true);
    }


}