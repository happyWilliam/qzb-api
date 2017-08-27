<?php
namespace App\Domain;

use App\Model\Program as Model;
use PhalApi\Exception\BadRequestException;

class Program {

    public function add($newData) {
        $model = new Model();

        $programs = $model->getProgramsByStartTime($newData['start_time']);
        if(count($programs) > 0) {
            throw new BadRequestException($newData['start_time'].'已经有活动：'.$programs['0']['name'], 1);
        }
        $newData['create_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        return $model->add($newData);
    }

    public function update($id, $newData) {
        $model = new Model();        
        $programs = $model->getProgramsByStartTime($newData['start_time']);        
        if(count($programs) > 1 || (count($programs) == 1 && $programs['0']['id'] != $id)) {
            throw new BadRequestException($newData['start_time'].'已经有活动：'.$programs['0']['name'], 1);
        }
        return $model->update($id, $newData);
    }

    public function getList($pageNo, $pageSize, $status, $name, $start_time, $end_time) {
        $rs = array('items' => array(), 'total' => 0);

        $model = new Model();
        $items = $model->getListItems($pageNo, $pageSize, $status, $name, $start_time, $end_time);
        $total = $model->getListTotal($pageNo, $pageSize, $status, $name, $start_time, $end_time);

        $rs['items'] = $items;
        $rs['total'] = $total;

        return $rs;
    }

    public function delete($id) {
        $model = new Model();
        return $model->delete($id);
    }
}
