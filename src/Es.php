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
     * 索引数据/更新数据
     * @param $id
     * @param $data
     * @return mixed
     */
    public function index($id,$data)
    {
        return $this->request('PUT',$id,$data);
    }

    /**
     * 删除
     * @param string $id
     * @return mixed
     */
    public function delete($id = null)
    {
        if(is_null($id)){
            //delete all documents of  current type
            $rt = $this->request('POST','_delete_by_query');
        }else{
            $rt = $this->request('DELETE',$id);
        }
        return $rt;
    }

    /**
     * 获取当前类型的文档总数量
     * @return mixed
     */
    public function count()
    {
        return $this->request('GET','_count');
    }

    /**
     * 获取指定ID的文档
     * @return mixed
     */
    public function id($id)
    {
        return $this->request('GET',$id);
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

    /**
     * 发送命令
     * @param   $method  GET,PUT,DELETE,etc
     * @param   $f      command
     * @param  string $data   
     * @return array         
     */
    public function request($method,$f,$data = '{}')
    {
        is_array($data) && $data = json_encode($data);
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