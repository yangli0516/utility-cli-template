<?php

namespace UtilityCli\Helper;


class Path
{
    /**
     * Get the parent path from a URL path.
     *
     * @param string $path
     * @return null|string
     */
    public static function getParent($path)
    {
        if ($path !== '/') {
            $last = strrpos($path, '/', -2);
            if ($last !== false) {
                return substr($path, 0, $last + 1);
            } else {
                return $path;
            }
        }
        return null;
    }

    /**
     * Generate a slug from a text.
     *
     * @param string $text
     * @return mixed|string
     */
    public static function generateCommonFilename($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Get the file name from an URL.
     *
     * @param string $url The URL text.
     * @param bool $ext Whether to return the file name with its extension.
     * @return string|null
     */
    public static function getFileNameFromURL($url, $ext = true)
    {
        $url = \Sabre\Uri\parse($url);
        if (isset($url['path'])) {
            $path = urldecode($url['path']);
            if ($ext) {
                return pathinfo($path, PATHINFO_BASENAME);
            } else {
                return pathinfo($path, PATHINFO_FILENAME);
            }
        }
        return null;
    }

    /**
     * Get the file extension from an URL.
     *
     * @param string $url The URL text.
     * @return null|string
     */
    public static function getFileExtensionFromURL($url)
    {
        $url = \Sabre\Uri\parse($url);
        if (isset($url['path'])) {
            $path = urldecode($url['path']);
            return strtolower(pathinfo($path, PATHINFO_EXTENSION));
        }
        return null;
    }

    /**
     * Get the unique identifier from an URL.
     *
     * @param string $url The URL text.
     * @return string
     */
    public static function getURLKey($url)
    {
        $url = \Sabre\Uri\parse($url);
        return strtolower(urldecode($url['path']));
    }
}
