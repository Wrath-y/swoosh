<?php

namespace Src\Log\Contract;

interface AppenderInterface
{
    public function format($data): string;
    public function writeToFile($data): bool;
    public function info($data): bool;
    public function warn($data): bool;
    public function debug($data): bool;
}