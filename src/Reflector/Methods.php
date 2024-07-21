<?php
namespace J2\ClassDebugger\Reflector;

use J2\ClassDebugger\Helper\ContainerHelper;
use J2\ClassDebugger\Helper\ReflectorHelper;
use J2\ClassDebugger\Service\CodeFormatter;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Methods
{
    use ContainerHelper, ReflectorHelper;

    private array $methods = [];

    protected Modifiers $modifiers;

    protected ReflectionClass $reflectionClass;

    public function __construct(Modifiers $modifiers, CodeFormatter $codeFormatter)
    {
        $this->modifiers = $modifiers;
        $this->codeFormatter = $codeFormatter;
    }

    /**
     * @param $class
     * @param string|null $type
     * @param array|null $except
     * @param bool $addModifiers
     * @param bool $addInfo
     * @return array[]
     * @throws ReflectionException
     */
    public static function get(
        $class,
        ?string $type = null,
        ?array $except = null,
        ?bool $addModifiers = null,
        bool $addInfo = false
    ) : array
    {
        $self = Self::instantiate(Self::class);

        $except ??= [];
        $type ??= 'all';
        $addModifiers ??= false;

        $self->reflectionClass = new ReflectionClass($class);

        $generator = $self->generateIndexedSet(
            $self->reflectionClass->getMethods(
                $self->modifiers->getType($type)
            )
        );

        foreach( $generator as $reflectionMethod)
        {
            if (!$self->toInclude($reflectionMethod, $except)) continue;

            if($self->setWithInformation('methods', $addInfo, $reflectionMethod, $addModifiers)) continue;

            $self->set('methods', $reflectionMethod, $addModifiers);
        }

        return $self->methods;
    }

    /**
     * @param ReflectionMethod $reflection
     * @param bool $addModifiers
     * @return array
     */
    protected function information(ReflectionMethod $reflection, ?bool $addModifiers): array
    {

        list($arguments, $parametersInfo) = $this->methodParameters($reflection->getParameters());

        return [
            'modifier' => $addModifiers ? $this->modifiers->get($reflection) : '',
            'name' => $reflection->name,
            'return_type' => $reflection->getReturnType() ? $reflection->getReturnType()->getName() : 'void',
            'arguments' => $arguments,
            'arguments_count' => $parametersInfo['count'],
            'parameters_info' => $parametersInfo['data'],
            'file_location' => $this->filename($reflection) . ' ' . $this->startEndLine($reflection),
            'phpdoc' => $this->codeFormatter->formatDoc($this->phpDoc($reflection)),
            'code' => $this->codeFormatter->format($this->code($reflection))
        ];
    }

    /**
     * @param array $reflectionParameters
     * @return array
     */
    protected function methodParameters(array $reflectionParameters): array
    {
        $count = 1;
        $arguments = [];
        $parametersData = [
            'data' => [],
            'count' => count($reflectionParameters)
        ];

        foreach($this->generateIndexedSet($reflectionParameters) as $param)
        {
            $parametersData['data'][$count]['index'] = $count;
            $parametersData['data'][$count]['name'] = $param->getName();
            $parametersData['data'][$count]['required'] = $this->optional($param->isOptional());
            $parametersData['data'][$count]['has_type'] = false;

            //$param is an instance of ReflectionParameter
            $arguments[] = $param->getName();

            if ($param->getType()) {
                $parametersData['data'][$count]['has_type'] = true;
                $parametersData['data'][$count]['allows_null'] = $this->trueOrFalse($param->getType()->allowsNull());
                $parametersData['data'][$count]['is_builtin'] = $this->trueOrFalse($param->getType()->isBuiltin());
                $parametersData['data'][$count]['type'] = $param->getType()->getName();
            }
            $count++;
        };

        return [$arguments, $parametersData];
    }

    /**
     * @param $reflection
     * @return mixed
     */
    protected function code($reflection): string
    {
        $filename = $this->filename($reflection);
        $start_line = $this->startLine($reflection) - 1; // it's actually - 1, otherwise you wont get the function() block
        $end_line = $this->endLine($reflection);
        $length = $end_line - $start_line;

        $source = file($filename);
        return print_r(implode("", array_slice($source, $start_line, $length)), true);
    }

    /**
     * @param bool $option
     * @return string
     */
    protected function trueOrFalse(bool $option): string
    {
        return ($option ? 'true' : 'false');
    }

    /**
     * @param bool $option
     * @return string
     */
    protected function optional(bool $option): string
    {
        return ($option ? 'optional' : 'required');
    }
}