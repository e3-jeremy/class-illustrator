<?php

namespace J2\ClassDebugger\Helper;

use Iterator;
use Reflector;

trait ReflectorHelper
{
    /**
     * @param array $array
     * @return Iterator
     */
    protected function generateObjectSet(array $array) : Iterator
    {
        foreach ($array as $value) {
            $input = yield  $value;

            if ($input === 'skip') {
                continue;
            }

            // this will break and return a value
            if ($input === 'break') {
                return;
            }
        }
    }
    /**
     * @param array $array
     * @return Iterator
     */
    protected function generateIndexedSet(array $array) : Iterator
    {
        $max = count($array);
        for ($i = 0; $i < $max; $i++) {
            $input = yield $array[$i];

            if ($input === 'skip') {
                continue;
            }

            // this will break and return a value
            if ($input === 'break') {
                return;
            }
        }
    }

    /**
     * @param array $array
     * @return Iterator
     */
    protected function generateNoneIndexedSet(array $array) : Iterator
    {
        foreach ($array as $key => $value) {
            $input = yield $key => $value;

            if ($input === 'skip') {
                continue;
            }

            // this will break and return a value
            if ($input === 'break') {
                return;
            }
        }
    }

    /**
     * @param Reflector $reflector
     * @param array $except
     * @return bool
     */
    protected function toInclude(Reflector $reflector, array $except): bool
    {
        if ($reflector->class == $this->reflectionClass->getName() &&
            !in_array($reflector->name, $except)) return true;

        return false;
    }

    /**
     * @param string $action
     * @param bool $addInfo
     * @param Reflector $reflector
     * @param bool $addModifiers
     * @param null $class
     * @return bool
     * @throws \ReflectionException
     */
    protected function setWithInformation(string $action, bool $addInfo, Reflector $reflector, bool $addModifiers, $class = null): bool
    {
        if ($addInfo) {
            $this->{$action}[] = $class ? $this->information($reflector, $addModifiers, $class): $this->information($reflector, $addModifiers);
            return true;
        }
        return false;
    }

    /**
     * @param string $action
     * @param Reflector $reflector
     * @param bool|null $addModifiers
     * @return void
     */
    protected function set(string $action, Reflector $reflector, ?bool $addModifiers = null): void
    {
        $addModifiers ??= false;

        $modifiers = '';
        if ($addModifiers) {
            $modifiers = $this->modifiers->get($reflector);
        }

        $this->{$action}[] = $modifiers . ' ' . $reflector->name;
    }

    /**
     * @param Reflector $reflector
     * @return string
     */
    protected function filename(Reflector $reflector): string
    {
        return $reflector->getFileName();
    }

    /**
     * @param Reflector $reflector
     * @return string
     */
    protected function startEndLine(Reflector $reflector): string
    {
        return $this->startLine($reflector) . ' - ' . $this->endLine($reflector);
    }

    /**
     * @param Reflector $reflector
     * @return string
     */
    protected function startLine(Reflector $reflector): string
    {
        return $reflector->getStartLine();
    }

    /**
     * @param Reflector $reflector
     * @return string
     */
    protected function endLine(Reflector $reflector): string
    {
        return $reflector->getEndLine();
    }

    /**
     * @param Reflector $reflector
     * @return string
     */
    protected function phpDoc(Reflector $reflector): string
    {
        return trim($reflector->getDocComment());
    }
}