<?php
namespace app\index\controller;
use think\Controller;
use app\common\controller\All;

class Base extends All
{
    var $_group;
    var $_user;

    public function __construct()
    {
        parent::__construct();
        $this->check_site_status();
        $this->label_maccms();
    }

    protected function check_site_status()
    {
        //站点关闭中
        if ($GLOBALS['config']['site']['site_status'] == 0) {
            $this->assign('close_tip',$GLOBALS['config']['site']['site_close_tip']);
            echo $this->fetch('public/close');
            die;
        }
    }

    protected function check_user_popedom($type_id,$popedom,$param=[],$flag='',$points=0,$trysee=0)
    {
        $user_id = intval(cookie('user_id'));
        $user_name = cookie('user_name');
        $user_check = cookie('user_check');
        $points = intval($points);

        if(!empty($user_id) || !empty($user_name) || !empty($user_check)){
            $res = model('User')->checkLogin();
            if($res['code'] == 1){
                $this->_user = $res['info'];
                $this->_group = $res['info']['group'];
            }
        }

        if(empty($this->_user) && empty($this->_group)){
            $group_list = model('Group')->getCache();
            $this->_group = $group_list[1];
        }
        $this->assign('user',$this->_user);
        $this->assign('group',$this->_group);

        $res = false;
        if(strpos(','.$this->_group['group_type'],','.$type_id.',')!==false && !empty($this->_group['group_popedom'][$type_id][$popedom])!==false){
            $res = true;
        }

        if($popedom==3){

            if($res===false && (empty($this->_group['group_popedom'][$type_id][5]) || $trysee==0)){
                return ['code'=>3001,'msg'=>'您没有权限访问此数据，请升级会员','trysee'=>0];
            }
            elseif($this->_group['group_id']<3 && empty($this->_group['group_popedom'][$type_id][3]) && !empty($this->_group['group_popedom'][$type_id][5]) && $trysee>0){
                return ['code'=>3002,'msg'=>'进入试看模式','trysee'=>$trysee];
            }
            elseif($this->_group['group_id']<3 && $points>0 && empty($this->_group['group_popedom'][$type_id][5]) ){
                $where=[];
                $where['ulog_mid'] = 1;
                $where['ulog_type'] = $flag=='play' ? 4 : 5;
                $where['ulog_rid'] = $param['id'];
                $where['ulog_sid'] = $param['sid'];
                $where['ulog_nid'] = $param['nid'];
                $where['user_id'] = $user_id;
                $where['ulog_points'] = $points;
                $res = model('Ulog')->infoData($where);
                if($res['code'] > 1) {
                    return ['code'=>3003,'msg'=>'观看此数据，需要支付【'.$points.'】积分，确认支付吗？','points'=>$points,'confirm'=>1,'trysee'=>0];
                }
            }
        }
        else{
            if($res===false){
                return ['code'=>1001,'msg'=>'您没有权限访问此页面，请升级会员组'];
            }
            if($popedom == 4){
                if( $this->_group['group_id'] ==1 && $points>0){
                    return ['code'=>4001,'msg'=>'此页面为收费数据，请先登录后访问！','trysee'=>0];
                }
                elseif($this->_group['group_id'] ==2 && $points>0){
                    $where=[];
                    $where['ulog_mid'] = 1;
                    $where['ulog_type'] = $flag=='play' ? 4 : 5;
                    $where['ulog_rid'] = $param['id'];
                    $where['ulog_sid'] = $param['sid'];
                    $where['ulog_nid'] = $param['nid'];
                    $where['user_id'] = $user_id;
                    $where['ulog_points'] = $points;
                    $res = model('Ulog')->infoData($where);

                    if($res['code'] > 1) {
                        return ['code'=>4003,'msg'=>'下载此数据，需要支付【'.$points.'】积分，确认支付吗？','points'=>$points,'confirm'=>1,'trysee'=>0];
                    }
                }
            }
            elseif($popedom==5){
                if(empty($this->_group['group_popedom'][$type_id][3]) && !empty($this->_group['group_popedom'][$type_id][5])){
                    $where=[];
                    $where['ulog_mid'] = 1;
                    $where['ulog_type'] = $flag=='play' ? 4 : 5;
                    $where['ulog_rid'] = $param['id'];
                    $where['ulog_sid'] = $param['sid'];
                    $where['ulog_nid'] = $param['nid'];
                    $where['user_id'] = $user_id;
                    $where['ulog_points'] = $points;
                    $res = model('Ulog')->infoData($where);

                    if($res['code'] == 1) {

                    }
                    elseif( $this->_group['group_id'] <=2 && $points <= intval($this->_user['user_points']) ){
                        return ['code'=>5001,'msg'=>'试看结束,是否支付[' . $points . ']积分观看完整数据？您还剩下[' . $this->_user['user_points'] . ']积分，请先充值！','trysee'=>$trysee];
                    }
                    elseif( $this->_group['group_id'] <3 && $points > intval($this->_user['user_points']) ){
                        return ['code'=>5002,'msg'=>'对不起,观看此页面数据需要[' . $points . ']积分，您还剩下[' . $this->_user['user_points'] . ']积分，请先充值！','trysee'=>$trysee];
                    }
                }
            }
        }

        return ['code'=>1,'msg'=>'权限验证通过'];
    }
}