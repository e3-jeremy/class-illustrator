<?php

namespace J2\ClassDebugger\Reflector;

use J2\ClassDebugger\Helper\ContainerHelper;
use J2\ClassDebugger\Helper\ReflectorHelper;
use ReflectionClass;
use ReflectionException;

class Traits
{
    use ContainerHelper, ReflectorHelper;

    protected array $traits = [];

    protected ReflectionClass $reflectionClass;

    public function __construct(Modifiers $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param $class
     * @param bool|null $addModifiers
     * @param bool $addInfo
     * @return array
     * @throws ReflectionException
     */
    public static function get(
        $class,
        ?bool $addModifiers = null,
        bool $addInfo = false
    ) : array
    {
        $self = Self::instantiate(Self::class);

        $addModifiers ??= false;

        $self->reflectionClass = new ReflectionClass($class);

        $generator = $self->generateNoneIndexedSet(
            $self->reflectionClass->getTraits()
        );

        foreach( $generator as $reflectionTrait)
        {
            if($self->setWithInformation('traits', $addInfo, $reflectionTrait, $addModifiers)) continue;

            $self->set('traits', $reflectionTrait, $addModifiers);
        };

        return $self->traits;
    }


    /**
     * @param ReflectionClass $reflection
     * @param bool $addModifiers
     * @return array
     * @throws ReflectionException
     */
    protected function information(ReflectionClass $reflection, bool $addModifiers) : array
    {
        return [
            'name' => $reflection->name,
            'modifier' => 'Trait',
            'methods' => Methods::get($reflection->name, null, null, true, true),
            'properties' => Properties::get($reflection->name, null, null, true, true)
        ];
    }

}