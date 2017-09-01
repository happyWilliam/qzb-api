<?php
namespace App\Common;
use \Firebase\JWT\JWT;
use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

class LoginFilter implements Filter {
    public function check() {
        $apis = \PhalApi\DI()->config->get('app.not_need_login_api');
        $service = \PhalApi\DI()->request->get('service');
        $key = \PhalApi\DI()->config->get('app.key');

        $login_jwt = \PhalApi\DI()->request->getHeader('login_jwt');
        
        // $decoded = JWT::decode($jwt, $key, array('HS256'));

        // 不在不需要验证登录的接口内，默认进行是否登录验证
        if(!in_array($service, $apis)) {
           
            // $login_info = \PhalApi\DI()->cache->get($token);

            // 如果没有登录，则抛出异常 402-没有登录
            // if(empty($login_jwt)) {
            if(FALSE) {
                throw new BadRequestException('请登录后进行该操作', 2);
            }else {
                // $login_info = JWT::decode($login_jwt, $key, array('HS256'));
                
            }          
        } 
    }
}
