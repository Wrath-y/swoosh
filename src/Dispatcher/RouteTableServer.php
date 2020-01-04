<?php

namespace Src\Dispatcher;

use Src\App;
use Swoole\Table;

class RouteTableServer
{
    private $table;

    public function __construct()
    {
        $config = App::get('config')->get('route_table');
        $this->table = new Table($config['size']);
        $this->table->column('type', Table::TYPE_STRING, 6);
        $this->table->column('controller', Table::TYPE_STRING, 100);
        $this->table->column('method', Table::TYPE_STRING, 20);
        $this->table->column('middleware', Table::TYPE_STRING, 20);
        $this->table->create();
    }

    public function set(string $key, array $value): bool
    {
        return $this->table->set($key, $value);
    }

    public function get(string $key)
    {
        return $this->table->get($key);
    }

    public function exist(string $key): bool
    {
        return $this->table->exist($key);
    }

    public function count(): int
    {
        return $this->table->count();
    }

    public function all()
    {
        $arr = [];
        foreach ($this->table as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }
}
