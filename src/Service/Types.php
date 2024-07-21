<?php

namespace J2\ClassDebugger\Service;

use stdClass;

class Types
{
    protected array $types = [
        'string' => '',
        'array' => [],
        'bool' => 1,
        'object' => null,
        'callable' => null,
    ];

    public function __construct()
    {
        $this->types['object'] = new stdClass();
        $this->types['callable'] = static fn() => '';
    }

    public function set(array $types)
    {
        $this->types = $types;
    }

    public function update($key, $value)
    {
        if($this->keyExists($key))  $this->types[$key] = $value;
    }

    public function get () : array
    {
        return $this->types;
    }

    public function getValue (string $key) : array
    {
        if($this->keyExists($key)) return $this->types[$key] ;

        return null;
    }

    public function getKeys () : array
    {
        return array_keys($this->types);
    }

    public function keyExists (string $key) : bool
    {
        if(array_key_exists($key, $this->types)) {
            return true;
        }
        return false;
    }


}