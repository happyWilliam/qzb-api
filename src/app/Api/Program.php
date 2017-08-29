<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Program as Domain;

/**
 * 活动管理操作
 * 
 */

class Program extends Api {

    public function getRules() {
        return array(
            'add' => array(
                'name' => array('name' => 'name', 'require' => true, 'min' => 1, 'max' => '100', 'desc' => '活动名称'),
                'description' => array('name' => 'description', 'min' => 1, 'max' => '300', 'desc' => '活动描述'),
                'imgs' => array('name' => 'imgs', 'desc' => '活动图片'),
                'start_time' => array('name' => 'start_time', 'require' => true, 'type' => 'date', 'desc' => '活动开始时间'),
                'end_time' => array('name' => 'end_time', 'require' => true, 'type' => 'date', 'desc' => '活动结束时间'),
                'address' => array('name' => 'address', 'require' => true, 'desc' => '活动地址'),
                'fee_type' => array('name' => 'fee_type', 'require' => true, 'type' => 'enum', 'range' => array('1', '2', '3'), 'desc' => '费用类型，1-会员制，2-AA制，3-其它'),
                'status' => array('name' => 'status', 'require' => true, 'desc' => '活动状态，0-活动已取消，1-活动报名中，2-活动报名截止，3-活动费用已核对，4-活动已结束'),
                'charge_user_id' => array('name' => 'charge_user_id', 'require' => true, 'min' => 1, 'desc' => '活动组织负责人ID'),                
            ),
            'signUp' => array(
                'participants' => array('name' => 'participants', 'require' => true, 'type' => 'array', 'desc' => '活动参加者'
                    // 'name' => array('name' => 'name', 'require' => true, 'min' => 1, 'max' => '100', 'desc' => '报名人姓名'),
                    // 'mobile' => array('name' => 'mobile', 'regex' => "/^1[34578]\d{9}$/", 'desc' => '手机号码'),
                    // 'program_id' => array('name' => 'program_id', 'require' => true, 'min' => 1, 'desc' => '活动ID'),
                    // 'gender' => array('name' => 'gender', 'require' => true, 'type' => 'enum', 'range' => array('0', '1', '-1'), 'desc' => '性别'),
                    // 'member_id' => array('name' => 'member_id', 'require' => true, 'min' => 1, 'desc' => '参与活动者的会员ID，如果是外带人员，这个ID为空'),            
                )
            ),
            'cancelSignUp' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'absence' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'get' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'getList' => array(
                'pageNo' => array('name' => 'pageNo', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'pageSize' => array('name' => 'pageSize', 'type' => 'int', 'min' => 1, 'default' => 10, 'desc' => '分页数量，默认10条'),
                'status' => array('name' => 'status', 'type' => 'int', 'desc' => '活动状态'),
                'name' => array('name' => 'login_namestatus', 'desc' => '活动名称'),
                'start_time' => array('name' => 'start_time', 'type' => 'date', 'desc' => '查询时间开始时间'),
                'end_time' => array('name' => 'end_time', 'type' => 'date', 'desc' => '查询时间结束时间'),
            ),            
            'update' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
                'name' => array('name' => 'name', 'require' => true, 'min' => 1, 'max' => '100', 'desc' => '活动名称'),
                'description' => array('name' => 'description', 'min' => 1, 'max' => '300', 'desc' => '活动描述'),
                'imgs' => array('name' => 'imgs', 'desc' => '活动图片'),
                'start_time' => array('name' => 'start_time', 'require' => true, 'type' => 'date', 'desc' => '活动开始时间'),
                'end_time' => array('name' => 'end_time', 'require' => true, 'type' => 'date', 'desc' => '活动结束时间'),
                'address' => array('name' => 'address', 'require' => true, 'desc' => '活动地址'),
                'fee_type' => array('name' => 'fee_type', 'require' => true, 'type' => 'enum', 'range' => array('1', '2', '3'), 'desc' => '费用类型，1-会员制，2-AA制，3-其它'),
                'charge_user_id' => array('name' => 'charge_user_id', 'require' => true, 'min' => 1, 'desc' => '活动组织负责人ID'),              
            ),  
            'updateFieldNum' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
                'field_num' => array('name' => 'field_num', 'require' => true, 'min' => 1, 'desc' => '活动场地数量'),
            ),    
            'cancel' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),               
            'stopSignUp' => array(
                'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'end' => array(
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
     * 报名活动
     * @desc 报名活动
     * @return int      id          活动ID
     */
    public function signUp() {        
       // 测试语句
       // http://127.0.0.4/public/?service=App.Program.SignUp&id=1&participants[0]={"name":"aaa","mobile";"13692110606","sign_member_id":"1","program_id":"1","gender":"1","member_id":"1"}&participants[1]={"name":"aaa","mobile";"13692110606","sign_member_id":"1","program_id":"1","gender":"1","member_id":"1"}
        $domain = new Domain();
       $participants = $this->participants;
       return $domain->signUp($participants);
    }

    /**
     * 取消报名
     * @desc 根据ID取消报名
     * @return int code 取消报名的结果，1表示成功，0表示无更新，false表示失败
     */
     public function cancelSignUp() {
        $rs = array();

        $domain = new Domain();
        $code = $domain->cancelSignUp($this->id);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 放鸽子
     * @desc 根据ID放鸽子
     * @return int code 放鸽子的结果，1表示成功，0表示无更新，false表示失败
     */
     public function absence() {
        $rs = array();
        
        $newData = array(
            'absence' => '1',
        );

        $domain = new Domain();
        $code = $domain->absence($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 获取数据
     * @desc 根据ID获取活动信息
     * @return int        id                 主键ID
     * @return string     name               活动名称
     * @return string     description        活动描述
     * @return string     imgs               活动图片
     * @return float      start_time         活动开始时间
     * @return string     end_time           活动结束时间
     * @return int        address            活动地址
     * @return int        status             活动状态
     * @return int        field_num          活动场地数量
     * @return array      fee_type           费用类型
     * @return array      charger            活动负责人
     * @return array      participants       参加活动者
     */
    public function get() {
        $domain = new Domain();
        $data = $domain->get($this->id);
        return $data;
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
        $list = $domain->getList($this->pageNo, $this->pageSize, $this->status, $this->name, $this->start_time, $this->end_time);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['pageNo'] = $this->pageNo;
        $rs['pageSize'] = $this->pageSize;

        return $rs;
    }

    /**
     * 修改活动
     * @desc 根据ID修改活动
     * @return int code 更新的结果，1表示成功，0表示无更新，false表示失败
     */
    public function update() {
        $rs = array();

        $newData = array(
            'name' => $this->name,
            'description' => $this->description,
            'imgs' => $this->imgs,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'address' => $this->address,
            'fee_type' => $this->fee_type,
            'charge_user_id' => $this->charge_user_id,
        );

        $domain = new Domain();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }
  
    /**
     * 修改活动场地数量
     * @desc 根据ID修改活动场地数量
     * @return int code 修改活动场地数量的结果，1表示成功，0表示无更新，false表示失败
     */
     public function updateFieldNum() {
        $rs = array();

        $newData = array(
            'field_num' => $this->field_num,
        );

        $domain = new Domain();
        $code = $domain->updateFieldNum($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 取消活动
     * @desc 根据ID取消活动
     * @return int code 取消活动的结果，1表示成功，0表示无更新，false表示失败
     */
     public function cancel() {
        $rs = array();

        $newData = array(
            'status' => '0',
        );

        $domain = new Domain();
        $code = $domain->cancel($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 截止报名活动
     * @desc 根据ID截止报名活动
     * @return int code 截止报名活动的结果，1表示成功，0表示无更新，false表示失败
     */
     public function stopSignUp() {
        $rs = array();

        $newData = array(
            'status' => '2',
        );

        $domain = new Domain();
        $code = $domain->stopSignUp($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 核对活动经费并结束活动
     * @desc 根据ID核对活动经费
     * @return int code 核对活动经费并结束活动的结果，1表示成功，0表示无更新，false表示失败
     */
     public function end() {
        $rs = array();

        $newData = array(
            'status' => '3',
        );

        $domain = new Domain();
        $code = $domain->end($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }
}
