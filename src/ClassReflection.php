<?php
namespace J2\ClassDebugger;

use AllowDynamicProperties;
use J2\ClassDebugger\Reflector\Modifiers;
use J2\ClassDebugger\Reflector\Properties;
use J2\ClassDebugger\Reflector\Methods;
use J2\ClassDebugger\Reflector\Traits;
use J2\ClassDebugger\Reflector\Constants;
use J2\ClassDebugger\Reflector\Parents;
use JetBrains\PhpStorm\NoReturn;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Simple Class Method Illustration
 *
 * @category  PHP
 * @author    Jeremie S. Yunsay <jeremie@legacyseller.com>
 * @copyright 2020 Jeremie S. Yunsay
 */
#[AllowDynamicProperties]
class ClassReflection
{
    use Helper\ContainerHelper;

    CONST ALLOWED_MEMORY = 1073741824;
    CONST OVERHEADS_ALLOWANCE = 770000000;

    private array $methodType = [
        'all' => null,
        'abstract' => ReflectionMethod::IS_ABSTRACT,
        'public' => ReflectionMethod::IS_PUBLIC,
        'protected' => ReflectionMethod::IS_PROTECTED,
        'private' => ReflectionMethod::IS_PRIVATE,
        'static' => ReflectionMethod::IS_STATIC,
        'final' => ReflectionMethod::IS_FINAL
    ];

    private array $nonClassType = [
        "boolean",
        "integer",
        "array",
        "NULL"
    ];

    /**
     * @var mixed
     */
    public static $toDebug = null;


    /**
     * @var ReflectionClass|null
     */
    private ?ReflectionClass $reflectionClass = null;

    /**
     *
     */
    public function __construct(
        Modifiers $modifiers,
        Properties $properties,
        Methods $methods,
        Traits $traits,
        Constants $constants,
        Parents $parents
    ) {
        $this->modifiers = $modifiers;
        $this->properties = $properties;
        $this->methods = $methods;
        $this->traits = $traits;
        $this->constants = $constants;
        $this->parents = $parents;
    }

    /**
     * Print the details of method in a class
     * $class: sample - Illuminate\Http\Request::class or $this, etc....
     *
     * @param mixed $toDebug : "object", "boolean", "integer", "double", "array", "resource", "NULL"
     * @param string|null $type
     * @param bool|string|null $methodNameOnly
     * @return void
     * @throws ReflectionException
     */
    #[NoReturn]
    final public static function print(
        $toDebug,
        ?string $type = null,
        ?bool $methodNameOnly = null
    ) : void
    {
        $self = Self::instantiate(Self::class);

        $methodNameOnly ??= false;
        $type ??= 'all';

        // Display only the list of methods in array
        if ($methodNameOnly)  Self::getMethods($toDebug, $type);

        $self->setClassName($toDebug);

        // Dump data is not a class, ex: array, string, etc ...
        if (!$self->reflectionClass)  Self::dump($toDebug);

        $header = $self->classNameAttributes();
        $properties = $self->properties->get($toDebug, $type, null, true, true);
        $methods = $self->methods->get($toDebug, $type, null, true, true);
        $traits = $self->traits->get($toDebug, true, true);
        $parents = $self->parents->get($toDebug, true, true);
        $constants = $self->constants->get($toDebug, true);
        $toDebug = $self->getDebug();

        Self::view('index', compact('header', 'methods', 'properties', 'traits', 'constants', 'parents', 'toDebug'));
    }

    /**
     * @param $toDebug
     * @return void
     */
    #[NoReturn]
    final public static function dump($toDebug) : void
    {
        Self::view('index', compact('toDebug'));
    }

    /**
     * Print the details of method in a class
     * $class: sample - Illuminate\Http\Request::class or $this, etc....
     *
     * @param instanceof $class
     * @return void
     */
    #[NoReturn]
    final public static function printExport($class): void
    {
        // This will display all info
        // 'export' was removed in 8.0 PHP version
        $toDebug = @ReflectionClass::export($class, true);

        Self::view('index', compact('toDebug'));
    }

    /**
     * @param $toDebug
     * @param string|null $type
     * @param array|null $except
     * @param bool|null $print
     * @param bool|null $addModifiers
     * @return array|null
     * @throws ReflectionException
     */
    final public static function getProperties(
        $toDebug,
        ?string $type = null,
        ?array $except = null,
        ?bool $print = null,
        ?bool $addModifiers = null
    ): ?array
    {
        $self = Self::instantiate(Self::class);

        $type ??= 'all';
        $except ??= [];
        $print ??= true;
        $addModifiers ??= true;

        $self->setClassName($toDebug);

        $toDebug = $self->properties->get($toDebug, $type, $except, $addModifiers);

        if(!$print) return $toDebug;

        Self::view('index', compact('toDebug'));
    }

    /**
     * @param $toDebug
     * @param string|null $type
     * @param array|null $except
     * @param bool|null $print
     * @param bool|null $addModifiers
     * @return array|null
     * @throws ReflectionException
     */
    final public static function getMethods(
        $toDebug,
        ?string $type = null,
        ?array $except = null,
        ?bool $print = null,
        ?bool $addModifiers = null
    ): ?array
    {
        $self = Self::instantiate(Self::class);

        $type ??= 'all';
        $except ??= [];
        $print ??= true;
        $addModifiers ??= true;

        $self->setClassName($toDebug);

        $toDebug = $self->methods->get($toDebug, $type, $except, $addModifiers);

        if(!$print) return $toDebug;

        Self::view('index', compact('toDebug'));
    }

    /**
     * @throws ReflectionException
     */
    public function __debugInfo(): array
    {
        $reflection = new ReflectionClass($this);
        return [
            'name' =>  $this->modifiers->get($reflection) . $reflection->getName(),
            'properties' => Self::getProperties(Self::class, 'all', null, false),
            'methods' => Self::getMethods(Self::class, 'all', null, false),
        ];
    }

    /**
     * @param $class
     * @return void
     */
    private function setClassName($class): void
    {
        Self::$toDebug = $class;
        if(in_array(gettype($class), $this->nonClassType)) return;
        try {
            $this->reflectionClass = new ReflectionClass($class);
        } catch (ReflectionException $error) {
            $this->reflectionClass = null;
        }
    }

    /**
     * Get The class name to debug
     *
     * @return string
     */
    private function className(): string
    {
        if (is_object(Self::$toDebug)) return get_class(Self::$toDebug);

        return Self::$toDebug;
    }

    /**
     * @return array
     */
    protected function classNameAttributes(): array
    {
        return [
            'modifier' => $this->modifiers->get($this->reflectionClass),
            'name' => $this->className(),
            'file_name' => $this->fileName(),
            'class_php_doc' => $this->classPHPDoc(),
        ];
    }

    /**
     * Class PHPDoc comment
     *
     * @return string
     */
    private function fileName(): string
    {
        return $this->reflectionClass->getFileName();
    }

    /**
     * Class PHPDoc comment
     *
     * @return string
     */
    private function classPHPDoc(): string
    {
        return trim($this->reflectionClass->getDocComment());
    }

    /**
     * @throws ReflectionException
     */
    private function getDebug(): array
    {
        $print = true;
        $before = memory_get_usage();
        $toDebug = Self::instantiate(Self::$toDebug);
        $after = memory_get_usage();
        $allocatedSize = ($after - $before);

        $exhausted = pow($allocatedSize, 2) + Self::OVERHEADS_ALLOWANCE;

        if (Self::ALLOWED_MEMORY < $exhausted) {
            $print = false;
            $toDebug = "Can't load the data, <br> Allowed memory size of 1073741824 bytes will be exhausted";
        }
        return compact('toDebug', 'print');
    }
}
