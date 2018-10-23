<?php

namespace Src\Resource;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Src\Helper\ComposerHelper;

class AnnotationResource
{
    protected $namespaces = [];
    protected $scanNamespaces = [];

    public function __construct(array $bootScan)
    {
        $this->namespaces = $bootScan;
    }

    public function addScanNamespace()
    {
        foreach ($this->namespaces as $key => $namespace) {
            $nsPath = ComposerHelper::getDirByNamespace($namespace);
            if (!$nsPath) {
                $nsPath = str_replace("\\", "/", $namespace);
                $nsPath = BASE_PATH . "/" . $nsPath;
            }
            $this->scanNamespaces[$namespace] = $nsPath;
        }
    }
}