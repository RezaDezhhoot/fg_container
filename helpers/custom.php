<?php

function array_value_recursive($key, array $arr): array
{
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) $val[] = $v;
    });
    return $val;
}


function emptyToNull($value)
{
    if (empty($value))
        return null;

    return $value;
}
