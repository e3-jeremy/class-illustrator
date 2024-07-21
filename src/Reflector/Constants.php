<?php

namespace J2\ClassDebugger\Reflector;

use J2\ClassDebugger\Helper\ContainerHelper;
use J2\ClassDebugger\Helper\ReflectorHelper;

use ReflectionClass;

class Constants
{
    use ContainerHelper, ReflectorHelper;

    private array $constants = [];

    protected Modifiers $modifiers;

    protected ReflectionClass $reflectionClass;

    public function __construct(Modifiers $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param $class
     * @param bool $addModifiers
     * @return array[]
     * @throws \ReflectionException
     */
    public static function get(
        $class,
        ?bool $addModifiers = null
    ) : array
    {
        $self = Self::instantiate(Self::class);

        $addModifiers ??= false;

        $self->reflectionClass = new ReflectionClass($class);

        $generator = $self->generateNoneIndexedSet(
            $self->reflectionClass->getConstants()
        );


        foreach($generator as $constant => $value)
        {
            $self->constants[] = $self->information($constant, $value, $addModifiers);
        }

        return $self->constants;
    }

    /**
     * @param string $constant
     * @param $value
     * @param bool $addModifiers
     * @return array
     */
    protected function information(string $constant, $value, bool $addModifiers) : array
    {

        return [
            'modifier' => ($addModifiers) ? 'CONST' : '',
            'name' => $constant,
            'type' => '',
            'value' => ['count' => !empty($value), 'data' => print_r($value, true)],
            'phpdoc' => ''
        ];
    }


}