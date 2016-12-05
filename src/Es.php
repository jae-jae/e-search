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

    public function __construct($config = null)
    {
        $config && $this->config = array_merge($this->config,$config);
        $this->http = new Client();
    }


    /**
     * 搜索
     * @param $data
     * @return mixed
     */
    public function search($data)
    {
        $f = '_search';
        if(is_string($data) && !EsTools::isJson($data))
        {
            $f .= '?q='.$data;
            $data = '';
        }
        return $this->request('GET',$f,$data);
    }

    /**
     * 设置映射
     * @param $config
     * @return mixed
     */
    public function setMapping($config)
    {
        $config = [
            'properties' => $config
        ];
        return $this->request('PUT','_mapping',$config);
    }

    /**
     * 索引数据
     * @param $id
     * @param $data
     * @return mixed
     */
    public function index($id,$data)
    {
        return $this->request('PUT',$id,$data);
    }

    public function setIndex($index)
    {
        $this->config['index'] = $index;
        return $this;
    }

    public function setType($type)
    {
        $this->config['type'] = $type;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }


    private function request($method,$f,$data)
    {
        is_array($data) && $data = json_encode($data,JSON_FORCE_OBJECT);
        $url = $this->makeRequestUrl($f);
        $response = $this->http->request($method,$url,[
            'body' => $data
        ]);
        return json_decode($response->getBody(),true);
    }

    private function makeRequestUrl($f = null)
    {
        $url = sprintf('%s/%s/%s',$this->config['server_url'],$this->config['index'],$this->config['type']);
        $f && $url = sprintf('%s/%s',$url,$f);
        return $url;
    }



}