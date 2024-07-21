<?php

namespace J2\ClassDebugger\Reflector;

use J2\ClassDebugger\Helper\StringManipulationHelper;
use ReflectionMethod;
use Reflector;

class Modifiers
{
    use StringManipulationHelper;
    protected array $isTypes = [
        'is_final' => 'Final',
        'is_interface' => 'Interface',
        'is_abstract' => 'Abstract',
        'is_public' => 'Public',
        'is_protected' => 'Protected',
        'is_private' => 'Private',
        'is_static' => 'Static',
        'is_read_only' => 'ReadOnly'
    ];

    protected array $types = [
        'all' => null,
        'abstract' => ReflectionMethod::IS_ABSTRACT,
        'public' => ReflectionMethod::IS_PUBLIC,
        'protected' => ReflectionMethod::IS_PROTECTED,
        'private' => ReflectionMethod::IS_PRIVATE,
        'static' => ReflectionMethod::IS_STATIC,
        'final' => ReflectionMethod::IS_FINAL
    ];

    /**
     * @param Reflector $reflection
     * @return string
     * @throws ReflectionException
     */
    final public function get(Reflector $reflection) : string
    {
        $modifiers = '';
        foreach ($this->isTypes as $modifier => $modifierName) {
            if($this->is($modifier, $reflection)) $modifiers .= $modifierName . ' ';
        }

        return $this->patch($modifiers);
    }

    /**
     * @param string $type
     * @return bool
     */
    final public function typeExists(string $type) : bool
    {
        if(array_key_exists($type, $this->types)) return true;

        return false;
    }

    /**
     * @param string $type
     * @return array
     */
    final public function getType(string $type) : ?string
    {
        if (!$this->typeExists($type)) $type = 'all';


        return $this->types[$type];
    }

    /**
     * @return array
     */
    final public function getTypes() : array
    {

        return array_values($this->isTypes);
    }

    /**
     * @param string $modifier
     * @param Reflector $reflection
     * @return bool
     */
    protected function is(string $modifier, Reflector $reflection) : bool
    {
        $this->camelCase($modifier);

        if(method_exists($reflection, $modifier) &&
            $reflection->{$modifier}()) return true;

        return false;
    }

    /**
     * @param string $modifiers
     * @return string
     */
    public function patch(string $modifiers): string
    {
        return str_replace('Interface Abstract', 'Interface', $modifiers);
    }
}