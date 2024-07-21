<?php

namespace J2\ClassDebugger\Reflector;

use J2\ClassDebugger\Helper\ContainerHelper;
use J2\ClassDebugger\Helper\ReflectorHelper;
use ReflectionClass;
use ReflectionException;

class Parents
{
    use ContainerHelper, ReflectorHelper;

    protected array $parents = [];

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
     * @throws \ReflectionException
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

        $parent = $self->reflectionClass->getParentClass();
        $interfaces = $self->reflectionClass->getInterfaceNames();
        if($parent) $interfaces[] = $parent->name;

        $generator = $self->generateIndexedSet($interfaces);

        foreach( $generator as $parent)
        {
            $reflectionClass = new ReflectionClass($parent);
            if($self->setWithInformation('parents', $addInfo, $reflectionClass, $addModifiers)) continue;

            $self->set('parents', $reflectionClass, $addModifiers);
        };

        return $self->parents;
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
            'modifier' => $addModifiers ? $this->modifiers->get($reflection) : '',
            'methods' => Methods::get($reflection->name, null, null, true, true),
            'properties' => Properties::get($reflection->name, null, null, true, true)
        ];
    }
}