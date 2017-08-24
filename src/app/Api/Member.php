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
                'mobile' => array('name' => 'mobile', 'min' => 1, 'max' => '11', 'desc' => '手机号码'),
                'gender' => array('name' => 'gender', 'type' => 'enum', 'range' => array('0', '1', '-1'))
            ),
            'get' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'getList' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
                'state' => array('name' => 'state', 'type' => 'int', 'default' => 0, 'desc' => '状态'),
            ),
            'update' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
                'login_name' => array('name' => 'login_name', 'require' => true, 'min' => 1, 'max' => '30', 'desc' => '登录用户名'),
                'real_name' => array('name' => 'real_name', 'min' => 1, 'max' => '30', 'desc' => '真实姓名'),
                'mobile' => array('name' => 'mobile', 'min' => 1, 'max' => '11', 'desc' => '手机号码'),
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
     * 删除数据
     * @desc 根据ID删除数据库中的一条纪录数据
     * @return int code 删除的结果，1表示成功，0表示失败
     */
    // public function delete() {
    //     $rs = array();

    //     $domain = new Domain();
    //     $code = $domain->delete($this->id);

    //     $rs['code'] = $code;
    //     return $rs;
    // }

    /**
     * 获取分页列表数据
     * @desc 根据状态筛选列表数据，支持分页
     * @return array    items   列表数据
     * @return int      total   总数量
     * @return int      page    当前第几页
     * @return int      perpage 每页数量
     */
    public function getList() {
        $rs = array();

        $domain = new Domain();
        $list = $domain->getList($this->state, $this->page, $this->perpage);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['page'] = $this->page;
        $rs['perpage'] = $this->perpage;

        return $rs;
    }
}
