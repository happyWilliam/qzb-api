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

class Member extends NotORM {

    protected function getTableName($id) {
        return 'member';
    }

    public function login($params) {
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, balance, gender, create_time')
            ->where('(login_name = ? OR mobile = ?)', $params['login_name'], $params['login_name'])
            ->where('pwd', $params['pwd'])
            ->where('del_flag', '0')
            ->where('status', 1);         
    }

    public function register($data, $id = NULL) {
        $this->formatExtData($data);        
        $notorm = $this->getORM($id);
        $notorm->insert($data);

        return $notorm->insert_id();    
    }    

    public function get($id, $fields = '*') {
        $needFields = is_array($fields) ? implode(',', $fields) : $fields;
        $notorm = $this->getORM($id);

        $table = $this->getTableName($id);

        $rs = $notorm->select($needFields)
            ->where($this->getTableKey($table), $id)
            ->where('del_flag', '0')
            ->fetch();

        $this->parseExtData($rs);

        return $rs;
    }

    public function getListItems($status, $pageNo, $pageSize, $login_name, $real_name, $balance) {
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, balance, gender, status, create_time')
            ->where('status', $status)
            ->where('login_name LIKE ?', '%'+$login_name+'%')
            ->where('real_name LIKE ?', '%'+$real_name+'%')
            ->where('balance < ?', $balance)
            ->order('create_time DESC')
            ->limit(($pageNo - 1) * $pageSize, $pageSize)
            ->fetchAll();
    }

    public function getListTotal($status) {
        $total = $this->getORM()
            ->where('status', $status)
            ->count('id');

        return intval($total);
    }
}
