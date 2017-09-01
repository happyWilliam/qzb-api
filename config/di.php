<?php
/**
 * DI依赖注入配置文件
 * 
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

use PhalApi\Loader;
use PhalApi\Config\FileConfig;
use PhalApi\Logger;
use PhalApi\Logger\FileLogger;
use PhalApi\Database\NotORMDatabase;

/** ---------------- 注册&初始化 基本服务组件 ---------------- **/

// 兼容PhalApi 1.x 旧版本
$loader = new Loader(API_ROOT, array('Library', 'library'));

$di = \PhalApi\DI();

// 自动加载
$di->loader = $loader;

// 配置
$di->config = new FileConfig(API_ROOT . '/config');

// 调试模式，$_GET['__debug__']可自行改名
$di->debug = !empty($_GET['__debug__']) ? true : $di->config->get('sys.debug');

// 日记纪录
$di->logger = new FileLogger(API_ROOT . '/runtime', Logger::LOG_LEVEL_DEBUG | Logger::LOG_LEVEL_INFO | Logger::LOG_LEVEL_ERROR);

// 数据操作 - 基于NotORM
$di->notorm = new NotORMDatabase($di->config->get('dbs'), $di->debug);

// 中文显示
$di->response = new \PhalApi\Response\JsonResponse(JSON_UNESCAPED_UNICODE); 

// 修改支持斜线路径请求
$di->request = new App\Common\Request();


/** ---------------- 定制注册 可选服务组件 ---------------- **/

/**
// 签名验证服务
$di->filter = new \PhalApi\Filter\SimpleMD5Filter();
 */

// 签名验证服务
$di->filter = new \App\Common\LoginFilter();

// 缓存 - Memcache/Memcached---这货坑了我两天时间
// 以下代码原先使用的是：MemcachedCache(\PhalApi\DI()->config->get('sys.mc'));,修改后使用了MemcacheCache
// 此外还需要安装memcached并启动 参考http://www.runoob.com/memcached/window-install-memcached.html
// php需要安装扩展，安装方式：
// 1.下载使用php版本对应的memcache扩展包，64位和32位感觉不太准，所以对应php版本的都下载下来  http://windows.php.net/downloads/pecl/releases/memcache/3.0.8/
// 2.解压包，将其中的php_memcache.dll复制到php  ext目录下，例如：E:\software\phpStudy\php\php-5.6.27-nts\ext
// 3.php-int文件最后面加一句：extension=php_memcache.dll 
// 4.重启应用即可
// $di->cache = function () {
//     return new \PhalApi\Cache\MemcacheCache(\PhalApi\DI()->config->get('sys.mc'));
// };


/**
// 支持JsonP的返回
if (!empty($_GET['callback'])) {
    $di->response = new \PhalApi\Response\JsonpResponse($_GET['callback']);
}
 */

