<?php
namespace App\Common;

use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

class LoginFilter implements Filter {
    public function check() {
        $apis = \PhalApi\DI()->config->get('app.not_need_login_api');
        $service = \PhalApi\DI()->request->get('service');

        // 不在不需要验证登录的接口内，默认进行是否登录验证
        if(!in_array($service, $apis)) {             
            
            // 设置
            \PhalApi\DI()->cache->set('thisYear', 2015, 600);
            // 获取，输出：2015
            echo PhalApi\DI()->cache->get('thisYear');

            // 删除
            PhalApi\DI()->cache->delete('thisYear');
            
            
            
            echo $out;
            // 如果没有登录，则抛出异常 402-没有登录
            if(TRUE) {
                throw new BadRequestException('请登录后进行该操作', 2);
            }
        } 
    }
}
