<?php

namespace J2\ClassDebugger\Helper;

trait StringManipulationHelper
{
    
    public function stringtolower($pattern): string
    {
        return strtolower($pattern);
    }


    /**
     * @param string $text
     * @return string
     */
    protected function snakeCase(string $text): string
    {
        return str_replace(' ', '', str_replace(['-', '_'], ' ', $text));
    }

    /**
     * @param string $text
     * @return string
     */
    protected function camelCase(string &$text): string
    {
        $text = str_replace(' ', '',
            ucwords(str_replace(['-', '_'],
                ' ', $text))
        );
        return $text;
    }

    /**
     * @param string $text
     * @return string
     */
    protected function capitalFirstLetter(string &$text): string
    {
        $text = ucwords($text);
        return $text;
    }
}