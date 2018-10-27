<?php

namespace Src\Server;

use Src\Resource\AnnotationResource;
use Src\App;

class DispatcherServer
{
    public $dispacher;

    public function __construct()
    {
        $bootScan =  App::getSupport('config')->get('bootScan');
        $resource = new AnnotationResource($bootScan);
        $resource->addScanNamespace();
        $resource->getDefinitions();
    }
}
