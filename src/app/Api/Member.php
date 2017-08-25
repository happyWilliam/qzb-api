<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Member as Domain;

/**
 * 会员管理操作
 * 
 */

class Member extends Api {

    public function getRules() {
        return array(
            'login' => array(
                'login_name' => array('name' => 'login_name', 'require' => true, 'min' => 1, 'max' => 30, 'desc' => '登录用户名或手机号码'),
                'pwd' => array('name' => 'pwd', 'require' => true, 'min' => 1, 'max' => 30, 'desc' => '登录密码'),
            ),
            'register' => array(
                'login_name' => array('name' => 'login_name', 'require' => true, 'min' => 1, 'max' => '30', 'desc' => '登录用户名'),
                'pwd' => array('name' => 'pwd', 'require' => true, 'min' => 1, 'max' => '30', 'desc' => '密码'),
                'real_name' => array('name' => 'real_name', 'min' => 1, 'max' => '30', 'desc' => '真实姓名'),
                'mobile' => array('name' => 'mobile', 'regex' => "/^1[34578]\d{9}$/", 'desc' => '手机号码'),
                'gender' => array('name' => 'gender', 'require' => true, 'type' => 'enum', 'range' => array('0', '1', '-1'), 'desc' => '性别')
            ),
            'get' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'getList' => array(
                'pageNo' => array('name' => 'pageNo', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'pageSize' => array('name' => 'pageSize', 'type' => 'int', 'min' => 1, 'default' => 10, 'desc' => '分页数量，默认10条'),
                'status' => array('name' => 'status', 'type' => 'int', 'desc' => '会员状态'),
                'login_name' => array('name' => 'login_namestatus', 'desc' => '登录用户名，模糊查询'),
                'real_name' => array('name' => 'real_name', 'desc' => '真实姓名，模糊查询'),
                'balance' => array('name' => 'balance', 'desc' => '余额小于值'),
            ),
            'update' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
                'login_name' => array('name' => 'login_name', 'require' => true, 'min' => 1, 'max' => '30', 'desc' => '登录用户名'),
                'real_name' => array('name' => 'real_name', 'min' => 1, 'max' => '30', 'desc' => '真实姓名'),
                'mobile' => array('name' => 'mobile', 'regex' => "/^1[34578]\d{9}$/", 'desc' => '手机号码'),
                'gender' => array('name' => 'gender', 'type' => 'enum', 'range' => array('0', '1', '-1')),
            ),            
            'delete' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'stop' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'active' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'recharge' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
                'balance' => array('name' => 'balance', 'type' => 'float', 'require' => true, 'min' => 1, 'desc' => '充值金额'),
            ),
            
        );
    }

    /**
     * 会员登录
     * @desc 会员登录
     * @return int      id          会员ID
     * @return string   login_name  登录名
     * @return string   real_name   真实姓名
     * @return string   mobile      手机号码
     * @return float    balance     余额
     * @return string   gender      性别 0-女，1-男，-1-未知
     * @return int      status      状态 1-启用，0-停用
     */
     public function login() {
        $domain = new Domain();
        $params = array(
            'login_name' => $this->login_name,
            'pwd' => $this->pwd,
            'mobile' => $this->login_name
        );
        $data = $domain->login($params);

        return $data;
    }

    /**
     * 注册会员
     * @desc 注册会员
     * @return int      id          会员ID
     */
    public function register() {
        $rs = array();

        $newData = array(
            'login_name' => $this->login_name,
            'pwd' => $this->pwd,
            'real_name' => $this->real_name,
            'mobile' => $this->mobile,
            'gender' => $this->gender,
        );

        $domain = new Domain();
        $id = $domain->register($newData);

        $rs['id'] = $id;
        return $rs; 
    }

    /**
     * 获取数据
     * @desc 根据ID获取会员信息
     * @return int      id          主键ID
     * @return string   login_name  登录名
     * @return string   real_name   真实姓名
     * @return string   mobile      手机号码
     * @return float    balance     余额
     * @return string   gender      性别 0-女，1-男，-1-未知
     * @return int      status      状态 1-启用，0-停用
     */
     public function get() {
        $domain = new Domain();
        $data = $domain->get($this->id, 'id,login_name,real_name,mobile,balance,gender,status');
        return $data;
    }

    /**
     * 修改会员
     * @desc 根据ID修改会员
     * @return int code 更新的结果，1表示成功，0表示无更新，false表示失败
     */
    public function update() {
        $rs = array();

        $newData = array(
            'login_name' => $this->login_name,
            'real_name' => $this->real_name,
            'mobile' => $this->mobile,
            'gender' => $this->gender,
        );

        $domain = new Domain();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 删除会员
     * @desc 根据ID删除会员
     * @return int code 删除的结果，1表示成功，0表示无更新，false表示失败
     */
     public function delete() {
        $rs = array();

        $newData = array(
            'del_flag' => '1',
        );

        $domain = new Domain();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 停用会员
     * @desc 根据ID停用会员
     * @return int code 停用的结果，1表示成功，0表示无更新，false表示失败
     */
     public function stop() {
        $rs = array();

        $newData = array(
            'status' => '0',
        );

        $domain = new Domain();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 启用会员
     * @desc 根据ID启用会员
     * @return int code 启用的结果，1表示成功，0表示无更新，false表示失败
     */
     public function active() {
        $rs = array();

        $newData = array(
            'status' => '1',
        );

        $domain = new Domain();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }


    /**
     * 获取分页列表数据
     * @desc 根据状态筛选列表数据，支持分页
     * @return array    items    列表数据
     * @return int      total    总数量
     * @return int      pageNo   当前第几页
     * @return int      pageSize 每页数量
     */
    public function getList() {
        $rs = array();       
        $domain = new Domain();
        $list = $domain->getList($this->status, $this->pageNo, $this->pageSize, $this->login_name, $this->real_name, $this->balance);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['pageNo'] = $this->pageNo;
        $rs['pageSize'] = $this->pageSize;

        return $rs;
    }
}
