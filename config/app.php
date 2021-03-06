<?php
/**
 * 请在下面放置任何您需要的应用配置
 *
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        // 'sign' => array('name' => 'sign', 'require' => true),
        // 1-PC，2-安卓，3-苹果手机
        'channel' => array('name' => 'channel', 'require' => false, 'default' => 1, 'desc' => '请求来源，1-PC，2-安卓，3-苹果手机'),
    ),

    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*            通配，全部接口服务，慎用！
     * - Site.*      Api_Default接口类的全部方法
     * - *.Index        全部接口类的Index方法
     * - Site.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'Site.Index',
    ),

    /**
     * 不需要filter进行登录验证拦截的接口名单
     *
     * 示例：
     */
    'not_need_login_api' => array(
        'App.Program.GetList',
    ),

    /**
     * 加密秘钥
     */
     'key' => 'cqmygysdssjtwmydtsgx',
);
