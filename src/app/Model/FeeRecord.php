<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class FeeRecord extends NotORM {

    protected function getTableName($id) {
        return 'fee_record';
    }
}
