<?php

namespace J2\ClassDebugger\Reflector;

use J2\ClassDebugger\Service\Types;
use ReflectionClass;
use ReflectionException;

final class DIContainer
{
    /**
     * @param $reflection
     * @return object|null
     * @throws ReflectionException
     */
    public static function instantiate($reflection) : ?object
    {
        $reflectionClass = $reflection;
        if(!$reflectionClass instanceof ReflectionClass) {
            $reflectionClass = new ReflectionClass($reflection);
        }

        if(!$reflectionClass->isInstantiable()) return null;

        $constructor = $reflectionClass->getConstructor();

        $dependencies = [];
        if($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                if($parameter->isOptional()) {
                    if($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                    }
                    continue;
                }
                $dependencies[] = self::provideValue($parameter);
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    /**
     * @throws ReflectionException
     */
    public static function provideValue($parameter)
    {
        $types = self::instantiate(Types::class);
        $type = null;
        if($parameter->getType()) {
            $type = $parameter->getType()->getName();
        }

        if($parameter->allowsNull()) return null;
        if($parameter->getType()) return self::instantiate($parameter->getType()->getName());
        if($parameter->canBePassedByValue()) {
            // TODO: apply postdata
            if($parameter->isDefaultValueAvailable()) return $parameter->getDefaultValue();
            if($type) {
                if($types->keyExists($type)) return $types->getValue($type);
            }
        }

        return '';
    }
}