<?php

namespace UtilityCli\Helper;

class Tree
{
    /**
     * Convert a flat array into a nested tree-like array.
     *
     * @param array $flatArray The flat array input. Each element must have keys of 'id' and 'parent_id'.
     * @param int $parentId The root parent ID.
     * @return array The result will be a tree-like array. The children of each element will be keyed by
     *   'children'.
     */
    public static function toNestedArray(array $flatArray, $parentId = 0)
    {
        $branch = [];
        foreach ($flatArray as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = self::toNestedArray($flatArray, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    /**
     * Convert a tree array into a flat array with parent ID.
     *
     * @param array $nestedArray The tree array. The children of each element will be keyed by 'children'.
     * @param int $parentID The root parent ID.
     * @return array The flat array. Each element will have 'id' and 'parent_id'.
     */
    public static function toFlatArray(array $nestedArray, $parentID = 0)
    {
        $flat = [];
        foreach ($nestedArray as $element) {
            $element['parent_id'] = $parentID;
            $flat[] = $element;
            if (!empty($element['children'])) {
                $children = self::toFlatArray($element['children'], $element['id']);
                $flat = array_merge($flat, $children);
            }
        }
        // unset children.
        foreach ($flat as &$item) {
            if (isset($item['children'])) {
                unset($item['children']);
            }
        }
        return $flat;
    }
}
