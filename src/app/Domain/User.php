<?php
namespace App\Domain;

use App\Model\User as Model;
use PhalApi\Exception\BadRequestException;

class User {

    public function login($params) {
        $model = new Model();
        $user = $model->getUserByLoginName($params['login_name']); 
        if(count($user) <= 0) {
            $user = $model->getUserByMobile($params['login_name']);
        } 
        
        if(count($user) <= 0) {
            throw new BadRequestException('用户 '.$params['login_name'].' 还未注册', 1);
        }
        
        if(count($user) > 0 && $user['0']['status'] == '0') {
            throw new BadRequestException('用户 '.$params['login_name'].' 已经被停用', 1);
        }        

        $user = $model->login($params);
        if(count($user) <= 0) {
            throw new BadRequestException('用户名或密码错误', 1);
        }
        return $model->login($params);
    }

    public function add($newData) {
        $model = new Model();
        $users = $model->getUserByLoginName($newData['login_name']);          
        if(count($users) > 0) {
            throw new BadRequestException('登录用户名 '.$newData['login_name'].' 已经被注册', 1);
        }
        $users = $model->getUserByMobile($newData['mobile']);
        if(count($users) > 0) {            
            throw new BadRequestException('手机号码 '.$newData['mobile'].' 已经被注册', 1);
        }
        $newData['create_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        return $model->add($newData);
    }

    public function update($id, $newData) {
        $model = new Model();        
        $users = $model->getUserByLoginName($newData['login_name']);

        // \PhalApi\DI()->response->setDebug('users', $users);
        
        if(count($users) > 1 || (count($users) == 1 && $users['0']['id'] != $id)) {
            throw new BadRequestException('登录用户名 '.$newData['login_name'].' 已经被注册', 1);
        }
        $users = $model->getUserByMobile($newData['mobile']);

        if(count($users) > 1 || (count($users) == 1 && $users['0']['id'] != $id)) {            
            throw new BadRequestException('手机号码 '.$newData['mobile'].' 已经被注册', 1);
        }
        return $model->update($id, $newData);
    }

    public function resetPwd($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }

    public function get($id) {
        $model = new Model();
        return $model->get($id, $fields);
    }


    public function getList($status, $pageNo, $pageSize, $login_name, $real_name) {
        $rs = array('items' => array(), 'total' => 0);

        $model = new Model();
        $items = $model->getListItems($status, $pageNo, $pageSize, $login_name, $real_name);
        $total = $model->getListTotal($status, $login_name, $real_name);

        $rs['items'] = $items;
        $rs['total'] = $total;

        return $rs;
    }

    public function delete($id) {
        $model = new Model();
        return $model->delete($id);
    }
}
