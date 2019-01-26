<?php

namespace Src\Server\Pool;

interface PoolInterface
{
    public function init();

    public function formatDB();

    public function getConnection();

    public function push($obj);

    public function gcSpareObject();
}