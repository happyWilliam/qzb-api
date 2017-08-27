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

class Program extends NotORM {

    protected function getTableName($id) {
        return 'program';
    }

    public function add($data, $id = NULL) {
        $this->formatExtData($data);        
        $notorm = $this->getORM($id);
        $notorm->insert($data);
        return $this->get($notorm->insert_id(), 'id, name, description, imgs, start_time, end_time, address, fee_type, status, charge_user_id');    
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

    public function getListItems($pageNo, $pageSize, $status, $name, $start_time, $end_time) {
        
        $sql = "SELECT id, name, start_time, end_time, address, fee_type, status, field_num, create_time FROM program";
        
        if($status !== null) {
            $sql .= " WHERE status = :status";
        }

        // 为了解决like语句的字符串拼接，费了老大劲儿了~
        if($name !== null) {            
            strpos($sql, 'WHERE') ? 
                ($sql .= " AND name LIKE CONCAT('%', :name, '%')") : 
                ($sql .= " WHERE name LIKE CONCAT('%', :name, '%')");
        }
        if($start_time !== null) {
            strpos($sql, 'WHERE') ? 
            ($sql .= " AND start_time >= :start_time") : 
            ($sql .= " WHERE start_time >= :start_time");
        }

        if($end_time !== null) {
            strpos($sql, 'WHERE') ? 
            ($sql .= " AND end_time <= :end_time") : 
            ($sql .= " WHERE end_time <= :end_time");
        }
        $sql .= " ORDER BY start_time ASC LIMIT ".($pageNo - 1) * $pageSize.",".$pageSize.";";
        $params = array(
            ':status' => $status,
            ':name' => $name,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
        ); 

        return $this->getORM()->queryAll($sql, $params);
    }

    public function getListTotal($pageNo, $pageSize, $status, $name, $start_time, $end_time) {
        $sql = "SELECT id FROM program";
        
        if($status !== null) {
            $sql .= " WHERE status = :status";
        }

        // 为了解决like语句的字符串拼接，费了老大劲儿了~
        if($name !== null) {            
            strpos($sql, 'WHERE') ? 
                ($sql .= " AND name LIKE CONCAT('%', :name, '%')") : 
                ($sql .= " WHERE name LIKE CONCAT('%', :name, '%')");
        }
        if($start_time !== null) {
            strpos($sql, 'WHERE') ? 
            ($sql .= " AND start_time >= :start_time") : 
            ($sql .= " WHERE start_time >= :start_time");
        }

        if($end_time !== null) {
            strpos($sql, 'WHERE') ? 
            ($sql .= " AND end_time <= :end_time") : 
            ($sql .= " WHERE end_time <= :end_time");
        }
        $params = array(
            ':status' => $status,
            ':name' => $name,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
        ); 
        
        $sql .= ";";
        $params = array(
            ':status' => $status,
            ':name' => $name,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
        ); 

        $total = count($this->getORM()->queryAll($sql, $params));
        return intval($total);
    }

    public function getProgramsByStartTime($start_time) {
        return $this->getORM()
            ->select('id, name, start_time, end_time, address')
            ->where('start_time', $start_time)
            ->fetchAll();
    }
}
