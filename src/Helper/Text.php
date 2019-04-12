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
        return trim($text);
    }

    /**
     * Remove BOM from the text.
     *
     * @param string $text
     * @return string
     */
    public static function removeBOM($text)
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
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


    /**
     * Decode HTML special character.
     *
     * @param string $text
     * @return string
     */
    public static function htmlSpecialCharDecode($text)
    {
        if (!empty($text)) {
            return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        }
        return '';
    }

    /**
     * Replace the line break characters within the text.
     *
     * @param string $text
     * @param string $replace
     *
     * @return null|string|string[]
     */
    public static function replaceLineBreaks($text, $replace = '<br>')
    {
        return preg_replace('/(\r\n|\n|\r)/', $replace, $text);
    }
}
