<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 04.07.2017
 * Time: 1:22
 */

namespace QueryBuilder\Helper;

class QueryBuilderHelper
{
    /**
     * @param mixed $value
     *
     * @return int|null|string
     */
    public static function escape($value)
    {
        switch (true) {
            case is_string($value):
                return '"' . addslashes($value) . '"';
            case is_bool($value):
                return (int)$value;
            case is_numeric($value):
                return $value;
            case is_object($value):
                return (string)$value;
            case is_array($value):
                array_walk($value, [QueryBuilderHelper::class, 'escape']);
                return implode(', ', $value);
            default:
                return null;
        }
    }
}