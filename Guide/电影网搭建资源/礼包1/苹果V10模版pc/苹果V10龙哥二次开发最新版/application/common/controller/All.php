<?php
namespace app\common\controller;
use think\Controller;

class All extends Controller
{
    var $_ref;
    public function __construct()
    {
        parent::__construct();
        $this->_ref = mac_get_refer();
    }

    protected function label_maccms()
    {
        $maccms = $GLOBALS['config']['site'];
        $maccms['path'] = MAC_PATH;
        $maccms['path_tpl'] = MAC_PATH_TEMPLATE;
        $maccms['user_status'] = $GLOBALS['config']['user']['status'];
        $maccms['search_hot'] = $GLOBALS['config']['app']['search_hot'];

        $controller = request()->controller();
        $action = request()->action();

        if(!empty($GLOBALS['mid'])) {
            $maccms['mid'] = $GLOBALS['mid'];
        }
        else{
            $maccms['mid'] = mac_get_mid($controller);
        }
        if(!empty($GLOBALS['aid'])) {
            $maccms['aid'] = $GLOBALS['aid'];
        }
        else{
            $maccms['aid'] = mac_get_aid($controller,$action);
        }

        $this->assign( ['maccms'=>$maccms] );
    }

    protected function label_comment()
    {
        $comment = config('maccms.comment');
        $this->assign('comment',$comment);
    }

    protected function label_type($view=0)
    {
        $param = mac_param_url();
        $this->assign('param',$param);
        $info = mac_label_type($param);
        $this->assign('obj',$info);
        if(empty($info)){
            return $this->error('获取分类失败，请选择其它分类！');
        }
        if($view<2) {
            $res = $this->check_user_popedom($info['type_id'], 1);
            if($res['code']>1){
                echo $this->error($res['msg']);
                exit;
            }
        }
        return $info;
    }

    protected function label_actor_index($total='')
    {
        $param = mac_param_url();
        $this->assign('param',$param);

        if($total=='') {
            $where = [];
            $where['actor_status'] = ['eq', 1];
            $total = model('Actor')->countData($where);
        }

        $url = mac_url_actor_index(['page'=>'PAGELINK']);
        $__PAGING__ = mac_page_param($total,1,$param['page'],$url);
        $this->assign('__PAGING__',$__PAGING__);
    }

    protected function label_actor_detail($info=[])
    {
        $param = mac_param_url();
        $this->assign('param',$param);
        if(empty($info)) {
            $res = mac_label_actor_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];
        }
        $this->assign('obj',$info);
        $comment = config('maccms.comment');
        $this->assign('comment',$comment);
        return $info;
    }


    protected function label_role_index($total='')
    {
        $param = mac_param_url();
        $this->assign('param',$param);

        if($total=='') {
            $where = [];
            $where['role_status'] = ['eq', 1];
            $total = model('Role')->countData($where);
        }

        $url = mac_url_topic_index(['page'=>'PAGELINK']);
        $__PAGING__ = mac_page_param($total,1,$param['page'],$url);
        $this->assign('__PAGING__',$__PAGING__);
    }

    protected function label_role_detail($info=[])
    {
        $param = mac_param_url();
        $this->assign('param',$param);
        if(empty($info)) {
            $res = mac_label_role_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];
        }
        $this->assign('obj',$info);
        $comment = config('maccms.comment');
        $this->assign('comment',$comment);

        return $info;
    }

    protected function label_topic_index($total='')
    {
        $param = mac_param_url();
        $this->assign('param',$param);

        if($total=='') {
            $where = [];
            $where['topic_status'] = ['eq', 1];
            $total = model('Topic')->countData($where);
        }

        $url = mac_url_topic_index(['page'=>'PAGELINK']);
        $__PAGING__ = mac_page_param($total,1,$param['page'],$url);
        $this->assign('__PAGING__',$__PAGING__);
    }

    protected function label_topic_detail($info=[])
    {
        $param = mac_param_url();
        $this->assign('param',$param);
        if(empty($info)) {
            $res = mac_label_topic_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];
        }
        $this->assign('obj',$info);

        $comment = config('maccms.comment');
        $this->assign('comment',$comment);

        return $info;
    }

    protected function label_art_detail($info=[],$view=0)
    {
        $param = mac_param_url();
        $this->assign('param',$param);

        if(empty($info)) {
            $res = mac_label_art_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];

            if(empty($info['art_tpl'])){
                $info['art_tpl'] = $info['type']['type_tpl_detail'];
            }
        }
        if($view <2) {
            $res = $this->check_user_popedom($info['type_id'], 2);
            if($res['code']>1){
                echo $this->error($res['msg']);
                exit;
            }
        }

        $this->assign('obj',$info);

        $url = mac_url_art_detail($info,['page'=>'PAGELINK']);

        $__PAGING__ = mac_page_param($info['art_page_total'],1,$param['page'],$url);
        $this->assign('__PAGING__',$__PAGING__);

        $this->label_comment();

        return $info;
    }

    protected function label_vod_detail($info=[],$view=0)
    {
        $param = mac_param_url();

        $this->assign('param',$param);
        if(empty($info)) {
            $res = mac_label_vod_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];

            if(empty($info['vod_tpl'])){
                $info['vod_tpl'] = $info['type']['type_tpl_detail'];
            }
            if(empty($info['vod_tpl_play'])){
                $info['vod_tpl_play'] = $info['type']['type_tpl_play'];
            }
            if(empty($info['vod_tpl_down'])){
                $info['vod_tpl_down'] = $info['type']['type_tpl_down'];
            }
        }
        if($view <2) {
            $res = $this->check_user_popedom($info['type']['type_id'], 2);
            if($res['code']>1){
                echo $this->error($res['msg']);
                exit;
            }
        }
        $this->assign('obj',$info);
        $this->label_comment();

        return $info;
    }

    protected function label_vod_role($info=[],$view=0)
    {
        $param = mac_param_url();
        $this->assign('param', $param);

        if (empty($info)) {
            $res = mac_label_vod_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];
        }
        $role = mac_label_vod_role(['rid'=>intval($info['vod_id'])]);
        if ($role['code'] > 1) {
            return $this->error($role['msg']);
        }
        $info['role'] = $role['list'];

        $this->assign('obj',$info);
    }

    protected function label_vod_play($flag='play',$info=[],$view=0,$pe=0)
    {
        $param = mac_param_url();
        $this->assign('param',$param);

        if(empty($info)) {
            $res = mac_label_vod_detail($param);
            if ($res['code'] > 1) {
                return $this->error($res['msg']);
            }
            $info = $res['info'];
        }

        $trysee = 0;
        $urlfun='mac_url_vod_'.$flag;
        $listfun = 'vod_'.$flag.'_list';
        if($view <2) {
            if ($flag == 'play') {
                $trysee = $GLOBALS['config']['user']['trysee'];
                if($info['vod_trysee'] >0){
                    $trysee = $info['vod_trysee'];
                }
                $popedom = $this->check_user_popedom($info['type_id'], ($pe==0 ? 3 : 5),$param,$flag,$info['vod_points_play'],$trysee);
            }
            else {
                $popedom =  $this->check_user_popedom($info['type_id'], 4,$param,$flag,$info['vod_points_down']);
            }
            $this->assign('popedom',$popedom);

            if($pe==0 && $popedom['code']>1 && empty($popedom["trysee"])){
                if($popedom['confirm']==1){
                    $this->assign('flag',$flag);
                    $this->assign('obj',$info);
                    echo $this->fetch('vod/confirm');
                    exit;
                }
                echo $this->error($popedom['msg']);
                exit;
            }
        }

        $player_info=[];
        $player_info['flag'] = $flag;
        $player_info['encrypt'] = intval($GLOBALS['config']['app']['encrypt']);
        $player_info['trysee'] = intval($trysee);
        $player_info['points'] = intval($info['vod_points_'.$flag]);
        $player_info['link'] = $urlfun($info,['sid'=>'{sid}','nid'=>'{nid}']);
        $player_info['link_next'] = $urlfun($info,['sid'=>$param['sid'],'nid'=>$param['nid']]);
        $player_info['link_pre'] = $player_info['link_next'];
        if($param['nid']>1){
            $player_info['link_pre'] = $urlfun($info,['sid'=>$param['sid'],'nid'=>$param['nid']-1]);
        }
        if($param['nid'] < $info['vod_'.$flag.'_list'][$param['sid']]['url_count']){
            $player_info['link_next'] = $urlfun($info,['sid'=>$param['sid'],'nid'=>$param['nid']+1]);
        }
        $player_info['url'] = (string)$info[$listfun][$param['sid']]['urls'][$param['nid']]['url'];
        $player_info['url_next'] = (string)$info[$listfun][$param['sid']]['urls'][$param['nid']+1]['url'];

        $player_info['from'] = (string)$info[$listfun][$param['sid']]['from'];
        $player_info['server'] = (string)$info[$listfun][$param['sid']]['server'];
        $player_info['note'] = (string)$info[$listfun][$param['sid']]['note'];

        if($GLOBALS['config']['app']['encrypt']=='1'){
            $player_info['url'] = mac_escape($player_info['url']);
            $player_info['url_next'] = mac_escape($player_info['url_next']);
        }
        elseif($GLOBALS['config']['app']['encrypt']=='2'){
            $player_info['url'] = base64_encode(mac_escape($player_info['url']));
            $player_info['url_next'] = base64_encode(mac_escape($player_info['url_next']));
        }

        $info['player_info'] = $player_info;
        $this->assign('obj',$info);

        if( $pe==0 && $flag=='play' && ($popedom['trysee']>0 )) {
            $dy_play = mac_url('index/vod/player',['id'=>$info['vod_id'],'sid'=>$param['sid'],'nid'=>$param['nid']]);
            $this->assign('player_data','');
            $this->assign('player_js','<div class="MacPlayer" style="z-index:99999;width:100%;height:100%;margin:0px;padding:0px;"><iframe id="player_if" name="player_if" src="'.$dy_play.'" style="z-index:9;width:100%;height:100%;" border="0" marginWidth="0" frameSpacing="0" marginHeight="0" frameBorder="0" scrolling="no" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen" ></iframe></div>');
        }
        else {
            $this->assign('player_data', '<script>var player_data=' . json_encode($player_info) . '</script>');
            $this->assign('player_js', '<script src="' . MAC_PATH . 'static/js/playerconfig.js"></script><script src="' . MAC_PATH . 'static/js/player.js"></script>');
        }
        $this->label_comment();
        return $info;
    }
}