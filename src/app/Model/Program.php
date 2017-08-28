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
        $sql = "SELECT t1.id, t1.name, t1.description, t1.imgs, t1.start_time, t1.end_time, t1.address, t1.fee_type_id, t1.status, t1.field_num, t1.charge_user_id, t1.participant_ids";
        $sql .= " FROM program AS t1, fee_type t2, user AS t3";
        $sql .= " WHERE t1.id = :id AND t1.charge_user_id = t3.id AND t1.fee_type_id = t2.id;";
        $params = array(
            ':id' => $id,
        ); 
        
        return $this->getORM()->queryAll($sql, $params)['0'];
    }

    public function getListItems($pageNo, $pageSize, $status, $name, $start_time, $end_time) {
        
        $sql = "SELECT id, name, start_time, end_time, address, fee_type, status, field_num FROM program";
        
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
