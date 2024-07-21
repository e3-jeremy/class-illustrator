<?php

namespace J2\ClassDebugger\Service;

use J2\ClassDebugger\Helper\ReflectorHelper;
use J2\ClassDebugger\Helper\StringManipulationHelper;
use J2\ClassDebugger\Reflector\Modifiers;

class CodeFormatter
{
    use ReflectorHelper, StringManipulationHelper;

    protected array $replacements = [];
    protected array $defaultFormat = [
        'type' => 'str_replace',
        'pattern' => null,
        'pattern_action' => null,
        'prefix' => '',
        'suffix' => '',
        'replacement' => '<span class="{{class}}" style="{{style}}">{{match}}</span>',
        'class' => '',
        'style' => '',
        'source' => null,
        'source_action' => null,
        'exclude' => [],
        'generator' => true,
        'callback' => ''
    ];

    /**
     * @param Modifiers $modifiers
     * @param Types $types
     */
    public function __construct(Modifiers $modifiers, Types $types)
    {
        $this->modifiers = $modifiers;
        $this->types = $types;
    }

    /**
     * @param string $code
     * @return string
     */
    public function format(string $code): string
    {
        $this->setReplacements();

        return $this->executeAllReplacements($code);
    }

    /**
     * @param string $code
     * @return string
     */
    public function formatDoc(string $code): string
    {
        $this->setDocReplacements();

        return $this->executeAllReplacements($code);
    }

    /**
     * @return void
     */
    protected function setDocReplacements(): void
    {
        $this->replacements = [
            'param' => [
                'type' => 'preg_replace',
                'pattern' => '/(@\w+)/',
                'replacement' => '<span style="color: grey;">$1</span>',
            ],
            'variables' =>  [
                'type' => 'preg_replace_callback',
                'pattern' => '/(\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)/',
                'style' => 'color: #7c95b3;',
                'callback' => 'highlight'
            ],
        ];
    }
    protected function setReplacements(): void
    {
        $this->replacements = [
            'modifiers' => [
                'class' => 'fw-bold fst-italic',
                'style' => 'color: #fffa0a;',
                'source_action' => 'stringToLower',
                'source' => $this->modifiers->getTypes(),
            ],
            'php_functions' => [
                'class' => 'fst-italic',
                'style' => 'color: #3cdd2a;',
                'source' => $this->allPHPFunctions(),
                'suffix' => '('
            ],
            'types' => [
                'pattern_action' => ['stringToLower'],
                'class' => 'code_types text-info fw-bold fst-italic',
                'source' => $this->types->getKeys(),
                'generator' => false
            ],
            'variables' =>  [
                'type' => 'preg_replace_callback',
                'pattern' => '/(\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)/',
                'style' => 'color: #67abfaff;',
                'callback' => 'highlight'
            ],
            'comment' =>  [
                'type' => 'preg_replace_callback',
                'pattern' => '/(\/\/.*)/',
                'style' => 'color: #65a14e;margin-bottom: -16px; margin-top: -16px; display: inline-block',
                'callback' => 'comment'
            ],
            'escape_html' =>  [
                'type' => 'preg_replace_callback',
                'pattern' => '/(["\'])(.*?)\1/',
                'callback' => 'escapeHtml',
            ],
            'static_methods_properties' =>  [
                'type' => 'preg_replace',
                'pattern' => '/(\b[\w]+)(\s*\()/',
                'replacement' => '<span style="color: #3cdd2a">$1</span>$2',
            ],
            'static_caller' =>  [
                'type' => 'preg_replace',
                'pattern' => '/(\b[\w]+)(::)/',
                'replacement' => '<span style="color: #00ffd2">$1</span>$2',
            ],
            'methods_properties' =>  [
                'type' => 'preg_replace',
                'pattern' => '/(->\s*[\w]+)/',
//                'pattern' => '/(->[a-zA-Z0-9_]+)(?!\()/',
                'replacement' => '<span style="color: #b5b368">$1</span>',
            ],
            'equal_operator' =>  [
                'type' => 'preg_split',
                'pattern' => '/(<[^>]*>|"[^"]*"|\'[^\']*\')/',
                'callback' => 'equalOperator',
                'style' => 'color: #f5c2f3;',
            ],
            'enclosure' =>  [
                'type' => 'preg_split',
                'pattern' => '/(<[^>]*>|"[^"]*"|\'[^\']*\'|[()\[\]{}])/',
                'style' => 'color: #f5c2f3;',
                'callback' => 'enclosure',
            ],
            'operator' => [
                'style' => 'color: #f5c2f3;',
                'source' => ['->', '+', '!=', '!==', '==', '::' ],
                'generator' => false
            ],
            'null' => [
                'source' => 'null',
                'pattern_action' => ['stringToLower'],
                'style' => 'color: #ff8429;',
                'class' => 'fst-italic',
                'generator' => false
            ],
            'void' => [
                'source' => 'void',
                'pattern_action' => ['stringToLower'],
                'style' => 'color: #ff8429;',
                'class' => 'fst-italic',
                'generator' => false
            ],
            'boolean' => [
                'source' => ['true', 'false'],
                'pattern_action' => ['stringToLower'],
                'style' => 'color: #ffc107;',
                'class' => 'fst-italic',
                'generator' => false
            ]
            ,'strip_tag_in_string' => [
                'type' => 'preg_replace_callback',
                'pattern' => '/(["\'])(.*?)\1/',
                'callback' => 'stripTagString',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function allPHPFunctions(): array
    {
        $functions = get_defined_functions();
        // Merge user and internal functions
        return array_merge($functions['user'], $functions['internal']);
    }

    /**
     * @param $matches
     * @param array|null $format
     * @return string
     */
    protected function comment($matches, ?array $format = null): string
    {
        // Remove HTML tags
        $comment = strip_tags($matches[1]);

        if(!$format) return htmlspecialchars($comment);
        return '<div style="' . $format['style'] . '">' . htmlspecialchars($comment) . '</div>';
    }

    /**
     * @param $matches
     * @param array|null $format
     * @return string
     */
    protected function highlight($matches, ?array $format = null): string
    {
        // Remove HTML tags
        $comment = strip_tags($matches[1]);

        if(!$format) return htmlspecialchars($comment);
        return '<span style="' . $format['style'] . '">' . htmlspecialchars($comment) . '</span>';
    }

    /**
     * @param $matches
     * @param array|null $format
     * @return string
     */
    protected function escapeHtml($matches, ?array $format = null): string
    {
        // Remove HTML tags
        return $matches[1] . preg_replace('/</', '&lt;', preg_replace('/>/', '&gt;', $matches[2])) . $matches[1];

    }

    /**
     * @param string $segment
     * @param array|null $format
     * @return string
     */
    protected function equalOperator(string $segment, ?array $format = null): string
    {
        if ($segment[0] !== '<' && $segment[0] !== '"' && $segment[0] !== "'") {
            return str_replace('=', '<span style="' . $format['style'] . '">=</span>', $segment);
        }
        return $segment;

    }

    /**
     * @param string $segment
     * @param array|null $format
     * @return string
     */
    protected function enclosure(string $segment, ?array $format = null): string
    {
        $segment = str_replace('(', '<span style="' . $format['style'] . '">(</span>', $segment);
        $segment = str_replace(')', '<span style="' . $format['style'] . '">)</span>', $segment);
        $segment = str_replace('[', '<span style="' . $format['style'] . '">[</span>', $segment);
        $segment = str_replace(']', '<span style="' . $format['style'] . '">]</span>', $segment);
        $segment = str_replace('{', '<span style="' . $format['style'] . '">{</span>', $segment);
        $segment = str_replace('}', '<span style="' . $format['style'] . '">}</span>', $segment);
        return $segment;

    }

    /**
     * @param string $segment
     * @param array|null $format
     * @return string
     */
    protected function stripTagString(array $matches, ?array $format = null): string
    {
        return $matches[1] . strip_tags($matches[2]) . $matches[1];

    }

    /**
     * @param string $code
     * @return string
     */
    protected function executeAllReplacements(string $code): string
    {
        foreach ($this->generateNoneIndexedSet($this->replacements) as $format) {
            // Provide default data if not exist in format
            $format = array_replace($this->defaultFormat, $format);
            $source = $format['source'];

            switch($format['type']) {
                case 'preg_replace':

                    $code = preg_replace(
                        $format['pattern'],
                        $format['replacement'],
                        $code
                    );
                    if ($format['callback']) $code = $format['callback']($code, $format);

                    break;

                case 'preg_split':

                    $segments = preg_split(
                        $format['pattern'],
                        $code,
                        -1,
                        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
                    );

                    // Apply the replacement function to each segment
                    $applied = array_map(function($segment) use ($format) {
                        return $this->{ $format['callback']}($segment, $format);
                    }, $segments);
                    // Reconstruct the text from the escaped segments
                    $code = implode('', $applied);

                    break;

                case 'preg_replace_callback':

                    $code = preg_replace_callback($format['pattern'], function ($matches) use ($format) {
                        return $this->{$format['callback']}($matches, $format);
                    }, $code);

                    break;

                default:  // str_replace
                    if (is_array($source)) {
                        foreach ($this->generateResources($format) as $source) {
                            $this->processStringReplace($format, $source, $code);
                        }
                        break;
                    }

                    $this->processStringReplace($format, $source, $code);
            }
        }

        return $code;
    }

    /**
     * @param array $format
     * @return array|object
     */
    protected function generateResources(array $format)
    {
        $resources = $format['source'];

        if ($format['generator']) return $this->generateIndexedSet($format['source']);
        return $resources;
    }

    /**
     * @param array $format
     * @param  $source
     * @param string $code
     * @return void
     */
    public function processStringReplace(array $format, $source, string &$code): void
    {
        // do some action
        if ($format['source_action']) $source = $this->{$format['source_action']}($source);
        // skip
        if (in_array($source, $format['exclude'])) return;

        $actions = $format['pattern_action'];
        if (!is_array($actions)) {
            $code = $this->executeStringReplace(null, $source, $code, $format);
            
            if ($format['callback']) $code = $format['callback']($code, $format);
            return;
        }

        foreach ($actions as $action) {
            $code = $this->executeStringReplace($action, $source, $code, $format);
        }
        if ($format['callback']) $code = $format['callback']($code, $format);
    }

    /**
     * @param string|null $action
     * @param string $source
     * @param string $code
     * @param array $format
     * @return string
     */
    protected function executeStringReplace(?string $action, string $source, string $code, array $format): string
    {
        $pattern = $this->resolvePattern($action, $source, $format);

        $code = str_replace(
            $format['prefix'] . $pattern . $format['suffix'],
            $format['prefix'] . $this->replacePattern($format, $source) . $format['suffix'],
            $code
        );
        if ($format['callback']) $code = $format['callback']($code);
        return $code;
    }

    /**
     * @param $action
     * @param $source
     * @param array $format
     * @return string
     */
    protected function resolvePattern($action, $source, array $format): string
    {
        $format ??= [];

        $pattern = $source;

        if(is_string($format['pattern_action'])) {
            $pattern = $this->{$format['pattern_action']}($pattern);
        }

        if($action) {
            $pattern = $this->{$action}($pattern);
        }

        return $pattern;
    }

    /**
     * @param $format
     * @param $replacement
     * @return string
     */
    protected function replacePattern($format, $replacement): string
    {
        $replaced = $format['replacement'];
        $replaced = str_replace('{{match}}', $replacement, $replaced);

        foreach(['class', 'style'] as $equivalent) {
            if(array_key_exists($equivalent, $format)) {
                $replaced = str_replace('{{' . $equivalent . '}}', $format[$equivalent], $replaced  );
            }
        }
        return $replaced;
    }
}