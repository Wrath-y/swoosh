<?php

namespace Src\Pool;

abstract class Pool implements PoolInterface
{
    public function init()
    {
        if ($this->inited) {
            return null;
        }

        for ($i = 0; $i < $this->min; $i++) {
            $obj = $this->formatDB();

            $this->count++;
            $this->connections->push($obj);
        }
        $this->inited = true;

        return $this;
    }

    protected abstract function createDb();

    public function formatDB()
    {
        $obj = null;
        $db = $this->createDb();
        if ($db) {
            $obj = [
                'last_used_time' => time(),
                'db' => $db,
            ];
        }

        return $obj;
    }

    public function getConnection()
    {
        $obj = null;
        if ($this->connections->isEmpty() && $this->count < $this->max) {
            $obj = $this->formatDB(); //连接数没达到最大，新建连接入池
            $this->count++;
        } else {
            $obj = $this->connections->pop($this->time_out);
        }

        return $obj;
    }

    public function push($obj)
    {
        if ($obj) {
            $this->connections->push($obj);
        }
    }

    public function gcSpareObject()
    {
        //大约1分钟检测一次连接
        swoole_timer_tick(env('DB_POOL_TIME_PICK', 6000), function () {
            if ($this->connections->length() > intval($this->max * 0.5)) {
                $list = [];
                while (true) {
                    if (!$this->connections->isEmpty()) {
                        $obj = $this->connections->pop($this->time_out);
                        $last_used_time = $obj['last_used_time'];
                        if ($this->count > $this->min && (time() - $last_used_time > $this->spareTime)) {//回收
                            $this->count--;
                        } else {
                            array_push($list, $obj);
                        }
                    } else {
                        break;
                    }
                }
                foreach ($list as $item) {
                    $this->connections->push($item);
                }
                unset($list);
            }
        });

        return $this;
    }
}
