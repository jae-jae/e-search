# e-search
Elasticsearch5 PHP Api

---

问：为什么官方已经有了Elasticsearch的PHP包，我还要写一个？
答：任性


# Install
```
composer require jaeger/e-search:dev-master
```

# Code Example

```php
$es = new \Jaeger\Es([
    //服务器地址
    'server_url'=>'http://localhost:9200',
    //索引
    'index' => 'news',
    //类型
    'type' => 'article'
]);

//or

$es = (new \Jaeger\Es())->setIndex('news')->setType('article');

```


## Mapping 设置映射
```php
$result = $es->setMapping([
   'title' => [
       'type' => 'text',
       'analyzer' => 'ik_smart'
   ],
   'content' => [
          'type' => 'text',
          'analyzer' => 'ik_smart'
      ]
]);
```

## Index/Update 索引数据/更新数据
```php
$result = $es->index(1,[
    'id' => 1,
    'title' => 'This is title',
    'content' => 'This is content'
]);
```

## Delete 删除数据
```php
//delete document for id 1
$result = $es->delete(1);
//delete all documents of  current type
$result = $es->delete();
```

## Count 获取当前类型的文档总数量
```php
$result = $es->count();
//or
$result = $es->request('GET','_count');
```

## Search　搜索

```
$result = $es->search($query);
```

`$query` can be an array,JSON string, or  string.

### 1.Array
```php
$query = [
    'query' => [
        'match' => [
            'content' => 'this is content'
        ]
    ],
    'highlight' => [
        'fields' => [
            //此处有坑
            'content' => (object)[]
        ]
    ]
];
```
### 2. JSON String
```php
$query = '{
              "query" : {
                  "match" : {
                      "content" : "this is content"
                  }
              },
              "highlight": {
                  "fields" : {
                      "content" : {}
                  }
              }
          }';
```
### 2. String
```php
$query = 'this is content';
//or
$query = 'content:this is content';
```

## Other Command 其它命令
```php
/**
 * send command 发送命令
 * @param  string           $method  GET,PUT,DELETE,etc
 * @param  string           $command '_search','_count','_mapping',etc
 * @param  array|jsonString $data 　　　send command　with data 
 */
$result = $es->request($method,$command,$data);

//example
$result = $this->request('GET','_search',[
    'query' => [
        'match' => [
            'content' => 'this is content'
        ]
    ]
]);

```

# Author

Jaeger <JaegerCode@gmail.com>