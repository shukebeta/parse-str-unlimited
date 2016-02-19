<?php

    function parse_str_unlimited($string, &$result) {
        if($string === '') return false;
        $result = array();
        // find the pairs "name=value"
        $pairs = explode('&', $string);
        $params = array();
        foreach ($pairs as $pair) {
            // use the original parse_str() on each element
            parse_str($pair, $params);
            $k = key($params);
            if(!isset($result[$k])) {
                $result += $params;
            } else {
                $result[$k] = array_merge_recursive_distinct($result[$k], $params[$k]);
            }
        }
        return true;
    }

    // better recursive array merge function listed on the array_merge_recursive PHP page in the comments
    function array_merge_recursive_distinct (array $array1, array $array2) {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
