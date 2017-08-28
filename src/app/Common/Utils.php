<?php
namespace App\Common;

class Utils {

    /**
     * 拷贝数组
     * @desc 拷贝数组
     * @param  array      $array           来源数组
     * @param  array      $exclude         需要忽略的字段
     * @return array      $result          新的数组
     */
    function arrayCopy(array $array, array $exclude) {
        $result = array();
        foreach( $array as $key => $val ) {
            if( is_array( $val ) ) {
                $result[$key] = arrayCopy( $val );
            } elseif ( is_object( $val ) && !in_array($key, $exclude)) {
                $result[$key] = clone $val;
            } else if(!in_array($key, $exclude)) {
                $result[$key] = $val;
            }
        }
        return $result;
    } 
}