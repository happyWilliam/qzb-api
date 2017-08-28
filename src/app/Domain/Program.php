<?php
namespace App\Domain;

use App\Model\Program as Model;
use App\Model\User as UserModel;
use App\Model\FeeType as FeeTypeModel;
use App\Model\Participant as ParticipantModel;
use App\Common\Utils as Utils;
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

    public function get($id) {
        $model = new Model();
        $userModel = new UserModel();
        $feeTypeModel = new FeeTypeModel();
        $participantModel = new ParticipantModel();
        $utils = new Utils();
        $program = $model->get($id);

        // todo... arrayCopy方法应该放在Util文件中
        // $rs = self::arrayCopy($program, array('charge_user_id', 'participant_ids', 'fee_type'));
        $rs = $utils->arrayCopy($program, array('charge_user_id', 'participant_ids', 'fee_type'));

        \PhalApi\DI()->response->setDebug('$rs', $rs); 

        $rs['charger'] = $userModel->get($program['charge_user_id'], 'id, login_name, real_name, mobile');

        $rs['fee_type'] = $feeTypeModel->get($program['fee_type_id'], 'id, name, min_num, max_num, remark');

        $rs['participants'] = $participantModel->getList($program['participant_ids']);

        return $rs;
    }

    /**
     * 报名活动
     * @desc 报名活动
     * @return int      id          活动ID
     */
    public function signUp($newData) {
        $model = new Model();

        $programs = $model->getProgramsByStartTime($newData['start_time']);
        if(count($programs) > 0) {
            throw new BadRequestException($newData['start_time'].'已经有活动：'.$programs['0']['name'], 1);
        }
        $newData['create_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        return $model->add($newData);
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
    public function updateFieldNum($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }

    public function cancel($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }
    public function stopSignUp($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }
    public function end($id, $newData) {
        $model = new Model(); 
        return $model->update($id, $newData);
    }
}