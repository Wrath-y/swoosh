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
    protected $time_out; //用于阻塞等待的时间
    protected $inited = false;
    protected $config = [];

    public function __construct()
    {
        $this->min = env('DB_POOL_MIN', 10);
        $this->max = env('DB_POOL_MAX', 100);
        $this->spareTime = env('DB_POOL_SPARE_TIME', 2 * 6000); // 2 minute
        $this->connections = new Channel($this->max + 1);
        $this->time_out = env('DB_POOL_TIME_OUT', 3);
        $this->config = App::get('config')->get('database.connections');
    }

    protected function createDb()
    {
        $name = $this->getDefaultConnection();
        $db = new \Swoole\Coroutine\Mysql();
        $db->connect([
            'host' => $this->config[$name]['host'],
            'port' => $this->config[$name] ['port'],
            'user' => $this->config[$name] ['username'],
            'password' => $this->config[$name] ['password'],
            'database' => $this->config[$name] ['database'],
            'fetch_mode' => true
        ]);

        return $db;
    }

    protected function getDefaultConnection()
    {
        return App::get('config')->get('database.default');
    }
}

