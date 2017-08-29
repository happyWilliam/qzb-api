<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**

CREATE TABLE `phalapi_curd` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `title` varchar(20) DEFAULT NULL,
    `content` text,
    `state` tinyint(4) DEFAULT NULL,
    `post_date` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

 */

class User extends NotORM {

    protected function getTableName($id) {
        return 'user';
    }

    public function login($params) {
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, create_time')
            ->where('(login_name = ? OR mobile = ?)', $params['login_name'], $params['login_name'])
            ->where('pwd', $params['pwd'])
            ->where('status', 1);         
    }

    public function add($data, $id = NULL) {
        $this->formatExtData($data);        
        $notorm = $this->getORM($id);
        $notorm->insert($data);
        return $this->get($notorm->insert_id(), 'id, login_name, real_name, mobile, pwd, create_time');    
    }

    public function get($id, $fields = '*') {
        $needFields = is_array($fields) ? implode(',', $fields) : $fields;
        $notorm = $this->getORM($id);

        $table = $this->getTableName($id);

        $rs = $notorm->select($needFields)
            ->where($this->getTableKey($table), $id)
            ->fetch();

        $this->parseExtData($rs);

        return $rs;
    }

    public function getListItems($status, $pageNo, $pageSize, $login_name, $real_name) {
        
        $condition = array();
        if($status !== null) {
            $condition['status'] = $status;
        }
        if($login_name != null) {
            $condition['login_name LIKE ?'] = '%'.$login_name.'%';
        }
        if($real_name != null) {
            $condition['real_name LIKE ?'] = '%'.$real_name.'%';
        }
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, status, create_time')
            ->where($condition)
            ->order('create_time DESC')
            ->limit(($pageNo - 1) * $pageSize, $pageSize)
            ->fetchAll();
    }

    public function getListTotal($status, $login_name, $real_name) {
        
        $condition = array();
        if($status !== null) {
            $condition['status'] = $status;
        }
        if($login_name != null) {           
            $condition['login_name LIKE ?'] = '%'.$login_name.'%';
        }
        if($real_name != null) {
            $condition['real_name LIKE ?'] = '%'.$real_name.'%';
        }

        $total = $this->getORM()
            ->where($condition)
            ->count('id');

        return intval($total);
    }

    public function getUserByLoginName($login_name) { 
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, status, create_time')
            ->where('login_name', $login_name)
            ->fetchAll();      
    }

    public function getUserByMobile($mobile) {
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, status, create_time')
            ->where('mobile', $mobile)
            ->fetchAll();
    }
}
