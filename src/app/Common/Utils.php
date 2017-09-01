<?php
namespace App\Common;

// 命令行窗口切换国内镜像，输入：composer config -g repo.packagist composer https://packagist.phpcomposer.com
// 命令行窗口运行  composer require "firebase/php-jwt:5.0.0"
use \Firebase\JWT\JWT;
class Utils {

    /**
     * 拷贝数组
     * @desc 拷贝数组
     * @param  array      $array           来源数组
     * @param  array      $exclude         需要忽略的字段
     * @return array      $result          新的数组
     */
    public function arrayCopy(array $array, array $exclude) {
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

    /**
     * JWT签发token
     * @desc JWT签发token
     * @param  array      $array        签发的token内容
     * @return string     $jwt          生成的jwt
     */
    public function JWTEncode(array $array) {
        $key = \PhalApi\DI()->config->get('app.key');
        $token = array(

            // jwt签发者
            "iss" => "wy",

            // jwt所面向的用户
            "sub" => "every_member",

            // jwt的过期时间，这个过期时间必须要大于签发时间,设置过期时间为请求时间10天后
            "exp" => $_SERVER['REQUEST_TIME']+10*24*60*60,

            // jwt的签发时间
            "iat" => $_SERVER['REQUEST_TIME'],
        );

        $token = array_merge($token, $array);

        $jwt = JWT::encode($token, $key);
        return $jwt;
    }

    /**
     * JWT解签token
     * @desc JWT解签token
     * @param  string    $jwt        jwt
     * @return array     $result     解签jwt的信息
     */
    public function JWTDecode(string $jwt) {
        $key = \PhalApi\DI()->config->get('app.key');

        $result = JWT::decode($jwt, $key, array('HS256'));
        return $result;
    }    
}