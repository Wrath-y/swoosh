<?php

namespace Src\Server;

use Src\App;
use Swoole\Table;

class RouteTableServer
{
    private $table;

    public function __construct()
    {
        $config = App::getSupport('config')->get('routeTable');
        $this->table = new Table($config['size']);
        $this->table->column('type', Table::TYPE_STRING, 6);
        $this->table->column('url', Table::TYPE_STRING, 100);
        $this->table->column('controller', Table::TYPE_STRING, 20);
        $this->table->column('method', Table::TYPE_STRING, 20);
        $this->table->create();
    }

    public function set($key, $value)
    {
        $this->table[$key] = $value;
    }

    public function get($key)
    {
    }
}
