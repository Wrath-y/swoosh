<?php

namespace Src\Server\Pool;

use Src\App;
use Swoole\Coroutine\Channel;
use Src\Server\Database\Connectors\ConnectionFactory;

class DBPool extends Pool
{
    protected $min; //最少连接数
    protected $max; //最大连接数
    protected $count; //当前连接数
    protected $connections; //连接池组
    protected $spareTime; //用于空闲连接回收判断
    protected $time_out = 6; //用于阻塞等待的时间
    protected $inited = false;
    protected $config = [];

    public function __construct()
    {
        $this->min = 10;
        $this->max = 100;
        $this->spareTime = 10 * 3600;
        $this->connections = new Channel($this->max + 1);
        $this->config = App::get('config')->get('database.connections');
    }

    protected function createDb()
    {
        return (new ConnectionFactory)->make($this->config['mysql']);
    }
}

