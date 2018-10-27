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
    protected $annotations = null;

    public function __construct(array $bootScan)
    {
        $this->namespaces = $bootScan;

    }

    /**
     * @param array $namespaces 配置文件中bootScan的命名空间
     */
    public function addScanNamespace()
    {
        foreach ($this->namespaces as $namespace) {
            $nsPath = ComposerHelper::getDirByNamespace($namespace);
            $nsPath = str_replace("\\", "/", $nsPath);
            $this->scanNamespaces[$namespace] = $nsPath;
        }
    }

    public function getDefinitions()
    {
        // 获取扫描的PHP文件
        $classNames = $this->registerLoaderAndScanBean();

        foreach ($classNames as $className) {
            $this->parseAnnotations($className);
        }
    }

    /**
     * 注册加载器和扫描PHP文件
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
     * 扫描目录下PHP文件
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
     * parse annotations
     *
     * @param string $className
     *
     * @return null
     */
    public function parseAnnotations(string $className)
    {
        if (!class_exists($className) && !interface_exists($className)) {
            return null;
        }
        $reflectionClass = new \ReflectionClass($className);
        $methodAnnotations = $reflectionClass->getDocComment();
        if ($methodAnnotations && preg_match('/@(.+?)\(\'(.+?)\'\)\n/i', $methodAnnotations, $methodAnnotation)) {
            $valiLength = count($methodAnnotation);
            if ($valiLength === 3 && $methodAnnotation[1] === 'Map') {
                DocResource::getRestful($methodAnnotation[2]);
            }
        }
        // 解析方法
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($publicMethods as $method) {
            $methodName = $method->getName();
            // 解析方法注解
            $methodAnnotations = $method->getDocComment();
            preg_match('/@(.+?)\n/i', $methodAnnotations, $methodAnnotation);
            dd($methodAnnotation);
            $this->annotations;
            $this->annotations[$className]['method'][$methodName][get_class($methodAnnotations)][] = $methodAnnotations;
        }
    }
}