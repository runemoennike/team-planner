<?php

namespace Core;

/**
 * Class InputParsing
 */
class InputParsing
{
    /**
     * Cleans up user input.
     *
     * @param string $unsafeStr
     * @return string
     */
    public static function cleanText($unsafeStr)
    {
        return strip_tags($unsafeStr);
    }
}
