<?php
/**
 * 默认接口服务类
 *
 * @author: Dm
 */
class Api_Regulator_TrainApply_Accept extends PhalApi_Api {

    public function getRules() {
        return array (
            'Go' => array(
                'applyId' => array('name' => 'apply_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '申请ID'),
            ),
        );
    }


    /**
     * 接收培训报名申请
     * #desc 用于接收培训报名申请
     * #return int code 操作码，0表示成功
     * #return int status 0 成功 1 失败
     */
    public function Go() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        //判断申请记录是否已存在
//        $trainApplyDomain = new Domain_Jiafubao_StaffTrainApply();
        $trainApplyDomain = new Domain_Jiafuyun_StaffTrainApply();
        $applyInfo = $trainApplyDomain->getBaseInfo($this->applyId);
        if( !$applyInfo){
            DI()->logger->debug('Apply not found', $this->applyId);

            $rs['code'] = 117;
            $rs['msg'] = T('Apply not exists');
            return $rs;
        }
        //判断申请状态
        if($applyInfo['status'] == 'wait'){
            $rs['code'] = 172;
            $rs['msg'] = T('Apply not submit');
            return $rs;
        }
        //是否已接收
        if($applyInfo['status'] == 'accept'){
            $rs['code'] = 173;
            $rs['msg'] = T('Apply have been accept');
            return $rs;
        }
        //是否已处理
        if($applyInfo['status'] == 'process'){
            $rs['code'] = 174;
            $rs['msg'] = T('Apply have been process');
            return $rs;
        }
        //是否已拒绝
        if($applyInfo['status'] == 'reject'){
            $rs['code'] = 175;
            $rs['msg'] = T('Apply have been reject');
            return $rs;
        }
        try {
            DI ()->notorm->beginTransaction ( 'db_api' );
            $res = $trainApplyDomain->acceptApply($this->applyId,$applyInfo);
            DI ()->notorm->commit( 'db_api' );

        } catch ( Exception $e ) {

            DI ()->notorm->rollback ( 'db_api' );
            $rs ['code'] = $e->getCode ();
            $rs ['msg'] = $e->getMessage ();
        }
        if($res){
            $status = 0;
        }else{
            $status = 1;
        }

        $rs['info']['status'] = $status;

        return $rs;
    }

}
