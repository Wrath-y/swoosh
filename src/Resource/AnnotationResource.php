<?php

namespace Src\Resource;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Src\Helper\ComposerHelper;
use Src\App;

class AnnotationResource
{
    protected $namespaces = [];
    protected $scanNamespaces = [];

    public function __construct(array $bootScan)
    {
        $this->namespaces = $bootScan;

    }

    /**
     * Scan the bootScan to get the real path
     */
    public function scanNamespace()
    {
        foreach ($this->namespaces as $namespace) {
            $nsPath = ComposerHelper::getDirByNamespace($namespace);
            $nsPath = str_replace("\\", "/", $nsPath);
            $this->scanNamespaces[$namespace] = $nsPath;
        }
    }

    public function getDefinitions()
    {
        // Get the PHP file under bootScan
        $classNames = $this->registerLoaderAndScanBean();
        foreach ($classNames as $className) {
            $this->parseAnnotations($className);
        }
    }

    /**
     * Get the PHP files
     *
     * @return array
     */
    protected function registerLoaderAndScanBean()
    {
        $phpClass = [];
        foreach ($this->scanNamespaces as $namespace => $dir) {
            $scanClass = $this->scanPhpFile($dir, $namespace);
            $phpClass = array_merge($phpClass, $scanClass);
        }

        return array_unique($phpClass);
    }

    /**
     * Scan the PHP file
     *
     * @param string $dir
     * @param string $namespace
     *
     * @return array
     */
    protected function scanPhpFile(string $dir, string $namespace)
    {
        if (!is_dir($dir)) {
            return [];
        }

        $iterator = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($iterator);
        $phpFiles = [];
        foreach ($files as $file) {
            $fileType = pathinfo($file, PATHINFO_EXTENSION);

            if ($fileType != 'php') {
                continue;
            }

            $replaces = ['', '\\', '', ''];
            $searches = [$dir, '/', '.php', '.PHP'];

            $file = str_replace($searches, $replaces, $file);

            $phpFiles[] = $namespace . $file;
        }

        return $phpFiles;
    }

    /**
     * Parse annotations and save to the swoole_table
     *
     * @param string $className
     * @param string $methodAnnotation[1] Get
     * @param string $methodAnnotation[2] demo/{id}
     * @param string $url[1] demo
     * @param string $url[2] {id}
     *
     * @return null
     */
    public function parseAnnotations(string $className)
    {
        if (!class_exists($className) && !interface_exists($className)) {
            return null;
        }
        // Parse the class by reflection
        $reflectionClass = new \ReflectionClass($className);
        $docResource = new DocResource();
        // Annotation of the parsing class
        $methodAnnotations = $reflectionClass->getDocComment();
        // Save to swoole_table
        if ($methodAnnotations && preg_match('/@Map\(\'(.+?)\'\)\n/i', $methodAnnotations, $methodAnnotation) && isset($methodAnnotation[1])) {
            $docResource->setRestful($methodAnnotation[1], $className);

            return;
        }
        // Parse the method by reflection
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($publicMethods as $method) {
            // Annotation of the parsing method
            $methodAnnotations = $method->getDocComment();
            if (!$methodAnnotations) {
                continue;
            }
            // Save to swoole_table
            if (preg_match('/@(.+?)\(\'(.+?)\'\)\n/i', $methodAnnotations, $methodAnnotation)) {
                if ($methodAnnotation[1] == 'Get' && preg_match('/(.+?)\/({.+?})/i', $methodAnnotation[2], $url)) {
                    $docResource->setShow($url[1], $className, $url[2]);
                } else {
                    $docResource->setByType($methodAnnotation[1], $methodAnnotation[2], $className);
                }
            }
        }
    }
}