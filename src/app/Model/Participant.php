<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Participant extends NotORM {

    protected function getTableName($id) {
        return 'participant';
    }   

    public function getList($participant_ids) {
        $pos = stripos($participant_ids,",");
        $sql = "SELECT id, name, mobile, sign_member_id, gender, member_id FROM participant WHERE id";
        if($pos < 0) {
            $sql .= " = ".$participant_ids;
        }else {
            $sql .= " IN (";
            $sql .= $participant_ids;
            $sql .= ");";
        }
        return $this->getORM()->queryAll($sql);
    }

    public function signUp($participants, $id = NULL) {
        $rs = array();
        // Step 1: 开启事务, 参数要写dbs.php中servers内设置的DB主键
        \PhalApi\DI()->notorm->beginTransaction('db_master');
    
        // Step 2: 数据库操作
        foreach($participants as $data) {
            // 当json_decode第二个参数为 TRUE 时，将返回 array 而非 object。哎~到处是坑
            array_push($rs, \PhalApi\DI()->notorm->participant->insert(json_decode($data, TRUE)));
        }
    
        // Step 3: 提交事务/回滚
        \PhalApi\DI()->notorm->commit('db_master');        
        //\PhalApi\DI()->notorm->rollback('db_master');        
        return implode(",", $rs);    
    }
}
