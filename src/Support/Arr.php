<?php

namespace Sanjarani\OpenAI\Support;

/**
 * Array utility helpers.
 */
class Arr
{
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string|int|null  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get(array $array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        if (strpos($key, ".") === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode(".", $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function has(array $array, $keys): bool
    {
        $keys = (array) $keys;

        if (!$array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;
            if (static::exists($array, $key)) {
                continue;
            }
            return false;
        }
        return true;
    }

    /**
     * Checks if the given key exists in the provided array.
     *
     * @param  array  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists(array $array, $key): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        }

        if (strpos($key, ".") === false) {
            return false;
        }

        foreach (explode(".", $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }
        return true;
    }
}

