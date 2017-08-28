<?php
namespace App\Domain;

use App\Model\Member as Model;
use PhalApi\Exception\BadRequestException;

class Member {

    public function login($params) {
        $model = new Model();
        $user = $model->getMemberByLoginName($params['login_name']); 
        if(count($user) <= 0) {
            $user = $model->getMemberByMobile($params['login_name']);
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

    public function register($newData) {
        $model = new Model();
        $members = $model->getMemberByLoginName($newData['login_name']);          
        if(count($members) > 0) {
            throw new BadRequestException('登录用户名 '.$newData['login_name'].' 已经被注册', 1);
        }
        $members = $model->getMemberByMobile($newData['mobile']);
        if(count($members) > 0) {            
            throw new BadRequestException('手机号码 '.$newData['mobile'].' 已经被注册', 1);
        }
        $newData['create_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        return $model->register($newData);
    }

    public function update($id, $newData) {
        $model = new Model();        
        $members = $model->getMemberByLoginName($newData['login_name']);

        // \PhalApi\DI()->response->setDebug('members', $members);
        
        if(count($members) > 1 || (count($members) == 1 && $members['0']['id'] != $id)) {
            throw new BadRequestException('登录用户名 '.$newData['login_name'].' 已经被注册', 1);
        }
        $members = $model->getMemberByMobile($newData['mobile']);

        if(count($members) > 1 || (count($members) == 1 && $members['0']['id'] != $id)) {            
            throw new BadRequestException('手机号码 '.$newData['mobile'].' 已经被注册', 1);
        }
        return $model->update($id, $newData);
    }

    public function resetPwd($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }

    public function active($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }

    public function stop($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }

    public function delete($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }

    public function get($id, $fields) {
        $model = new Model();
        return $model->get($id, $fields);
    }

    public function getList($status, $pageNo, $pageSize, $login_name, $real_name, $balance) {
        $rs = array('items' => array(), 'total' => 0);
        $model = new Model();
        $items = $model->getListItems($status, $pageNo, $pageSize, $login_name, $real_name, $balance);
        $total = $model->getListTotal($status, $login_name, $real_name, $balance);

        $rs['items'] = $items;
        $rs['total'] = $total;

        return $rs;
    }    
}
