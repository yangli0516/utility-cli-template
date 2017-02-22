<?php

namespace UtilityCli\Helper;


class Text
{
    /**
     * Convert a text into a single line string.
     *
     * @param string $text
     * @return string
     */
    public static function toSingleLine($text)
    {
        if (strpos($text, "\n") !== false) {
            return trim(preg_replace('/\s+/', ' ', $text));
        }
        return $text;
    }

    /**
     * Remove tab characters from a text.
     *
     * @param string $text
     * @return string
     */
    public static function removeTabs($text)
    {
        if (strpos($text, "\t") !== false) {
            return str_replace("\t", " ", $text);
        }
        return $text;
    }

    /**
     * Generate a snippet from a text.
     *
     * @param string $text
     * @param int $length The length of the snippet.
     * @return string
     */
    public static function toSnippet($text, $length = 500)
    {
        $text = self::toSingleLine($text);
        $text = self::removeTabs($text);
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length);
        }
        return $text;
    }
}
