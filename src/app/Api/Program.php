<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Program as Domain;

/**
 * 管理员管理操作
 * 
 */

class Program extends Api {

    public function getRules() {
        return array(
            'add' => array(
                'name' => array('name' => 'login_name', 'require' => true, 'min' => 1, 'max' => '100', 'desc' => '活动名称'),
                'description' => array('name' => 'description', 'min' => 1, 'max' => '300', 'desc' => '活动描述'),
                'imgs' => array('name' => 'imgs', 'desc' => '活动图片'),
                'start_time' => array('name' => 'start_time', 'require' => true, 'type' => 'date', 'desc' => '活动开始时间'),
                'end_time' => array('name' => 'end_time', 'require' => true, 'type' => 'date', 'desc' => '活动结束时间'),
                'address' => array('name' => 'address', 'require' => true, 'desc' => '活动地址'),
                'fee_type' => array('name' => 'fee_type', 'require' => true, 'type' => 'enum', 'range' => array('1', '2', '3'), 'desc' => '费用类型，1-会员制，2-AA制，3-其它'),
                'status' => array('name' => 'status', 'require' => true, 'desc' => '活动状态，0-活动已取消，1-活动报名中，2-活动报名截止，3-活动费用已核对，4-活动已结束'),
                'charge_user_id' => array('name' => 'charge_user_id', 'require' => true, 'min' => 1, 'desc' => '活动组织负责人ID'),                
            ),
            'get' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'getList' => array(
                'pageNo' => array('name' => 'pageNo', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'pageSize' => array('name' => 'pageSize', 'type' => 'int', 'min' => 1, 'default' => 10, 'desc' => '分页数量，默认10条'),
                'status' => array('name' => 'status', 'type' => 'int', 'desc' => '管理员状态'),
                'login_name' => array('name' => 'login_namestatus', 'desc' => '登录用户名，模糊查询'),
                'real_name' => array('name' => 'real_name', 'desc' => '真实姓名，模糊查询'),
            ),
            'update' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
                'login_name' => array('name' => 'login_name', 'require' => true, 'min' => 1, 'max' => '30', 'desc' => '登录用户名'),
                'real_name' => array('name' => 'real_name', 'min' => 1, 'max' => '30', 'desc' => '真实姓名'),
                'mobile' => array('name' => 'mobile', 'regex' => "/^1[34578]\d{9}$/", 'desc' => '手机号码'),
            ),                 
            'cancel' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
        );
    }

    /**
     * 新增活动
     * @desc 新增活动
     * @return int      id          活动ID
     */
    public function add() {
        $newData = array(
            'name' => $this->name,
            'description' => $this->description,
            'imgs' => $this->imgs,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'address' => $this->address,
            'fee_type' => $this->fee_type,
            'status' => $this->status,
            'charge_user_id' => $this->charge_user_id,
        );
        $domain = new Domain();
        return $domain->add($newData);
    }

    /**
     * 修改管理员
     * @desc 根据ID修改管理员
     * @return int code 更新的结果，1表示成功，0表示无更新，false表示失败
     */
    public function update() {
        $rs = array();

        $newData = array(
            'login_name' => $this->login_name,
            'real_name' => $this->real_name,
            'mobile' => $this->mobile
        );

        $domain = new Domain();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 重置管理员密码
     * @desc 根据ID重置管理员密码
     * @return int    code 重置管理员密码的结果，1表示成功，0表示无更新，false表示失败
     * @return string pwd  重置后的密码
     */
     public function resetPwd() {
        $rs = array();

        $newData = array(
            'pwd' => 'sq123456',
        );

        $domain = new Domain();
        $code = $domain->resetPwd($this->id, $newData);

        $rs['code'] = $code;
        $rs['pwd'] = $newData['pwd'];
        return $rs;
    }

    /**
     * 删除管理员
     * @desc 根据ID删除管理员
     * @return int code 删除的结果，1表示成功，0表示无更新，false表示失败
     */
     public function delete() {
        $rs = array();

        $domain = new Domain();
        $code = $domain->delete($this->id);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 停用管理员
     * @desc 根据ID停用管理员
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
     * 启用管理员
     * @desc 根据ID启用管理员
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
        $list = $domain->getList($this->status, $this->pageNo, $this->pageSize, $this->login_name, $this->real_name);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['pageNo'] = $this->pageNo;
        $rs['pageSize'] = $this->pageSize;

        return $rs;
    }
}
