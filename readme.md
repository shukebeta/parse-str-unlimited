# parse-str-unlimited：a better parse_str alternative

parse_str 遵循 php.ini max_input_vars 限制实在荒谬透顶。感谢 [sebastianwebb](https://gist.github.com/sebastianwebb) 在 <https://gist.github.com/rubo77/6821632> 中的精采评论，这个无限制版 parse_str_unlimit 的所有功能都归功于他。建立这个项目的目的，一是希望引出更好的实现，二是希望更多的人能更容易地找到它。

为方便大家copy/paste，我把代码也顺手粘到这里：

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
