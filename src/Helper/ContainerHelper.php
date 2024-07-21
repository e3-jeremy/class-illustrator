<?php

namespace J2\ClassDebugger\Helper;

use J2\ClassDebugger\Reflector\DIContainer;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;

trait ContainerHelper
{
    /**
     * Instantiate the class
     *
     * @param $class
     * @return object
     * @throws ReflectionException
     */
    final public static function instantiate($class): object
    {
        return DIContainer::instantiate($class);
    }

    /**
     * @param string $filename
     * @param array $data
     * @return void
     */
    #[NoReturn]
    final public static  function view(string $filename, array $data = []): void
    {
        $file = str_replace('.', '/', $filename);
        extract($data);

        ob_start();

        include_once (__DIR__ . '/../view/layout/template.php');

        echo ob_get_clean();

        die();
    }


}