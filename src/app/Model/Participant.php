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
}
