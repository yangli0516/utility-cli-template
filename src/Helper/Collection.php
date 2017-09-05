<?php

namespace UtilityCli\Helper;


class Collection
{
    /**
     * Find an element based on values of specific keys.
     *
     * @param array $items An list of elements. Each element is an
     *   array contains several fields.
     * @param array $criteria The searching criteria. Multiple fields
     *   can be used for a criteria.
     * @return null|array Returns the found element or null if not found.
     */
    public static function search(array $items, array $criteria)
    {
        foreach ($items as $item) {
            $match = true;
            foreach ($criteria as $key => $value) {
                if ($item[$key] !== $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Group an array of associative arrays by the key of each element.
     *
     * @param array $items
     * @param $key
     * @return array
     */
    public static function group(array $items, $key)
    {
        $grouped = [];
        foreach ($items as $item) {
            $groupKey = $item[$key];
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [];
            }
            $grouped[$groupKey][] = $item;
        }
        return $grouped;
    }
}
