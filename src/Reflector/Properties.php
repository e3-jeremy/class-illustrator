<?php

namespace J2\ClassDebugger\Reflector;

use Exceptionption;
use J2\ClassDebugger\Helper\ContainerHelper;
use J2\ClassDebugger\Helper\ReflectorHelper;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Properties
{
    use ContainerHelper, ReflectorHelper;

    protected array $properties = [];

    protected Modifiers $modifiers;

    protected ReflectionClass $reflectionClass;

    public function __construct(Modifiers $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param $class
     * @param string|null $type
     * @param array|null $except
     * @param bool $addModifiers
     * @param bool $addInfo
     * @return array
     * @throws ReflectionException
     */
    public static function get(
        $class,
        ?string $type = null,
        ?array $except = null,
        bool $addModifiers = false,
        bool $addInfo = false
    ) : array
    {
        $self = Self::instantiate(Self::class);

        $except ??= [];
        $type ??= 'all';

        $self->reflectionClass = new ReflectionClass($class);

        $generator = $self->generateIndexedSet(
            $self->reflectionClass->getProperties(
                $self->modifiers->getType($type)
            )
        );

        foreach( $generator as $reflectionProperty)
        {
            if(!$self->toInclude($reflectionProperty, $except)) continue;

            if($self->setWithInformation('properties', $addInfo, $reflectionProperty, $addModifiers, $class)) continue;

            $self->set('properties', $reflectionProperty, $addModifiers);
        };

        return $self->properties;
    }

    /**
     * @param $class
     * @param ReflectionProperty $reflection
     * @return array
     * @throws ReflectionException
     */
    protected function information(ReflectionProperty $reflection, $addModifiers, $class) : array
    {
        $reflection->setAccessible(true);
        $class = DIContainer::instantiate($class);
        $value = ($class)? $reflection->getValue($class) : [];

        return [
            'modifier' => ($addModifiers) ? $this->modifiers->get($reflection) : '',
            'name' => $reflection->name,
            'type' => ($reflection->getType()) ? $reflection->getType()->getName() : '',
            'value' => ['count' => !empty($value), 'data' => print_r($value, true)],
            'phpdoc' => $this->phpDoc($reflection)
        ];
    }
}