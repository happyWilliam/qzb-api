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
        return $this->get($notorm->insert_id(), 'id, login_name, real_name, mobile, pwd, balance, gender, create_time');  
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
        if($balance != null) {
            $condition['balance < ?'] = $balance;
        }
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, balance, gender, status, create_time')
            ->where($condition)
            ->order('create_time DESC')
            ->limit(($pageNo - 1) * $pageSize, $pageSize)
            ->fetchAll();
    }

    // public function getListItems2($status, $pageNo, $pageSize, $login_name, $real_name, $balance) {

    //     $sql = "SELECT id, login_name, real_name, mobile, balance, gender, status, create_time FROM member";
    //     \PhalApi\DI()->response->setDebug('status', $status);
    //     \PhalApi\DI()->response->setDebug('login_name', $login_name);
    //     \PhalApi\DI()->response->setDebug('real_name', $real_name);
    //     \PhalApi\DI()->response->setDebug('balance', $balance);
    //     if($status !== null) {
    //         $sql .= " WHERE status = :status";
    //     }

    //     // 为了解决like语句的字符串拼接，费了老大劲儿了~
    //     if($login_name !== null) {            
    //         strpos($sql, 'WHERE') ? 
    //             ($sql .= " AND login_name LIKE CONCAT('%', :login_name, '%')") : 
    //             ($sql .= " WHERE login_name LIKE CONCAT('%', :login_name, '%')");
    //     }
    //     if($real_name !== null) {
    //         strpos($sql, 'WHERE') ? 
    //         ($sql .= " AND real_name LIKE CONCAT('%', :real_name, '%')") : 
    //         ($sql .= " WHERE real_name LIKE CONCAT('%', :real_name, '%')");
    //     }
    //     if($balance !== null) {            
    //         strpos($sql, 'WHERE') ? ($sql .= " AND balance < :balance") : ($sql .= " WHERE balance < :balance");
    //     }
    //     $sql .= " ORDER BY create_time DESC LIMIT ".($pageNo - 1) * $pageSize.",".$pageSize.";";
    //     $params = array(
    //         ':status' => $status,
    //         ':login_name' => $login_name,
    //         ':real_name' => $real_name,
    //         ':balance' => $balance,
    //     ); 

    //     return $this->getORM()->queryAll($sql, $params);
    // }

    public function getListTotal($status, $login_name, $real_name, $balance) {

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
        if($balance != null) {
            $condition['balance < ?'] = $balance;
        }

        $total = $this->getORM()
            ->where($condition)
            ->count('id');

        return intval($total);
    }

    public function getMemberByLoginName($login_name) { 
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, balance, gender, status, create_time')
            ->where('login_name', $login_name)
            ->fetchAll();      
    }

    public function getMemberByMobile($mobile) {
        return $this->getORM()
            ->select('id, login_name, real_name, mobile, balance, gender, status, create_time')
            ->where('mobile', $mobile)
            ->fetchAll();
    }

}
