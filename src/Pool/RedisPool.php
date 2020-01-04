<?php

namespace Src\Pool;

use Src\App;
use Swoole\Coroutine\Channel;
use Src\Redis\RedisManager;

class RedisPool extends Pool
{
    protected $min; //最少连接数
    protected $max; //最大连接数
    protected $count; //当前连接数
    protected $connections; //连接池组
    protected $spareTime; //用于空闲连接回收判断
    protected $time_out; //用于阻塞等待的时间
    protected $inited = false;
    protected $config = [];

    public function __construct()
    {
        $this->min = env('REDIS_POOL_MIN', 10);
        $this->max = env('REDIS_POOL_MAX', 100);
        $this->spareTime = env('REDIS_POOL_SPARE_TIME', 10 * 3600); // 2 minute
        $this->connections = new Channel($this->max + 1);
        $this->time_out = env('REDIS_POOL_TIME_OUT', 3);
        $this->config = App::get('config')->get('database.redis');
    }

    protected function createDb()
    {
        return (new RedisManager($this->config))->connection();
    }
}
