<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class FeeType extends NotORM {

    protected function getTableName($id) {
        return 'fee_type';
    }
}
