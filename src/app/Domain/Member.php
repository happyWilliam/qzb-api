<?php
namespace App\Domain;

use App\Model\Member as Model;

class Member {

    public function login($params) {
        $model = new Model();
        return $model->login($params);
    }

    public function register($newData) {
        $newData['create_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

        $model = new Model();
        return $model->register($newData);
    }

    public function update($id, $newData) {
        $model = new Model();
        return $model->update($id, $newData);
    }

    public function get($id, $fields) {
        $model = new Model();
        return $model->get($id, $fields);
    }

    public function delete($id) {
        $model = new Model();
        return $model->delete($id);
    }

    public function getList($state, $page, $perpage) {
        $rs = array('items' => array(), 'total' => 0);

        $model = new Model();
        $items = $model->getListItems($state, $page, $perpage);
        $total = $model->getListTotal($state);

        $rs['items'] = $items;
        $rs['total'] = $total;

        return $rs;
    }
}
